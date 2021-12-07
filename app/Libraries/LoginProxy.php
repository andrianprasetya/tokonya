<?php

namespace App\Libraries;

use App\Exceptions\InvalidCredentialsException;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Attempting as EventAuthAttempt;
use Illuminate\Auth\Events\Authenticated as EventAuthAuthenticated;
use Illuminate\Auth\Events\Failed as EventAuthFailed;
use Illuminate\Auth\Events\Login as EventAuthLogin;
use Illuminate\Auth\Events\Logout as EventAuthLogout;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class LoginProxy
{
    private $cookie;

    private $auth;

    private $app;

    private $encrypter;

    private $config;

    private $request;

    private $database;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->auth = $app->make('auth');
        $this->config = $app->make('config');
        $this->cookie = $app->make('cookie');
        $this->encrypter = $app->make('encrypter');
        $this->request = $app->make('request');
        $this->database = $app->make('db');
    }

    public function attemptLogin($credentials)
    {
        return $this->proxy($credentials, 'password');
    }

    public function attemptRefresh($token = '')
    {
        $decryptor = null;
        if (empty($token)) {
            $decryptor = $this->encrypter->decrypt($this->request->cookie('refreshToken'));
        } else {
            $decryptor = $token;
        }
        return $this->proxy([
            'refresh_token' => $decryptor
        ], 'refresh_token');
    }

    private function proxy(array $data = [], $grantType = 'password')
    {
        try {
            $data = array_merge([
                'client_id' => $this->config->get('skeleton.auth.client_id'),
                'client_secret' => $this->config->get('skeleton.auth.client_secret'),
                'grant_type' => $grantType
            ], $data);

            $credentials = [
                'email' => $data['username'],
                'password' => $data['password'],
            ];

            event(new EventAuthAttempt($this->getGuard(), $credentials, false));

            $user = $this->getUserProvider()->retrieveByCredentials($credentials);

            if (is_null($user)) {
                event(new EventAuthFailed($this->getGuard(), $user, $credentials));
                throw InvalidCredentialsException::forUsername($credentials['email']);
            }

            $client = new Client();
            $url = $this->config->get('skeleton.auth.url');
            $guzzleResponse = $client->post(sprintf('%s/%s', $this->config->get('app.url'), $url), [
                'form_params' => $data
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $guzzleResponse = $e->getResponse();
        } catch (InvalidCredentialsException $e) {
            $response = response()->json(['message' => 'Invalid credentials'], 400);
            return $response;
        }

        $response = json_decode($guzzleResponse->getBody());
        if (property_exists($response, "access_token")) {
            /*$encryptedToken = $this->encrypter->encrypt($response->refresh_token);
            $this->cookie->queue('refreshToken',
                $encryptedToken,
                604800,
                null,
                null,
                false,
                true
            );*/
            $response = [
                'token_type' => $response->token_type,
                'access_token' => $response->access_token,
                'expires_in' => $response->expires_in,
                'refresh_token' => $response->refresh_token,
            ];
        }
        // Event
        event(new EventAuthAuthenticated($this->getGuard(), $user));
        event(new EventAuthLogin($this->getGuard(), $user, false));

        $response = response()->json($response);
        $response->setStatusCode($guzzleResponse->getStatusCode());
        $headers = $guzzleResponse->getHeaders();
        foreach ($headers as $headerType => $headerValue) {
            $response->header($headerType, $headerValue);
        }
        return $response;
    }

    /**
     * Logs out the user. We revoke access token and refresh token.
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $user = auth()->user();
        $accessToken = auth()->user()->token();
        $this->database->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);
        event(new EventAuthLogout($this->getGuard(), $user));
        $accessToken->revoke();
    }

    /**
     * Get the guard.
     *
     * @return string
     */
    protected function getGuard()
    {
        return auth()->guard('api');
    }

    /**
     * Get the user instance.
     *
     * @return \Illuminate\Contracts\Auth\UserProvider
     */
    protected function getUserProvider(): UserProvider
    {
        return Auth::getProvider();
    }
}