<?php

namespace App\Http\Controllers\Api\Auth;

use App\Libraries\ResponseStd;
use App\Models\Merchant;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Lcobucci\JWT\Parser;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Contracts\Validation\Validator;
use Laravel\Passport\Http\Controllers\AccessTokenController as OauthController;
use League\OAuth2\Server\AuthorizationServer;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use Laravel\Passport\Http\Controllers\HandlesOAuthErrors;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Events\Logout as EventAuthLogout;

class MerchantTokenController extends OauthController
{
    use ValidatesRequests, HandlesOAuthErrors;

    /**
     * The authorization server.
     *
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;

    /**
     * The token repository instance.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokens;

    /**
     * The JWT parser instance.
     *
     * @var Parser
     */
    protected $jwt;

    /**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer $server
     * @param  \Laravel\Passport\TokenRepository $tokens
     * @param  Parser $jwt
     * @return void
     */
    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt
    ) {
        parent::__construct($server, $tokens, $jwt);
        $this->jwt = $jwt;
        $this->server = $server;
        $this->tokens = $tokens;
    }


    public function issueToken(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();
        $response = parent::issueToken($request);

        if ($response->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
            return ResponseStd::fail('Incorrect phone number or password.', Response::HTTP_UNAUTHORIZED);
        } else {
            if ($response->getStatusCode() === Response::HTTP_OK) {
                $user = Merchant::query()
                    ->where('email', '=', $body['username'])
                    ->first();

                $oauthContent = json_decode($response->getContent());

                $dataRresponse = [
                    'token_type' => $oauthContent->token_type,
                    'access_token' => $oauthContent->access_token,
                    'expires_in' => $oauthContent->expires_in,
                    'refresh_token' => $oauthContent->refresh_token,
                    'id' => $user->id,
                    'email' => $user->email,
                    'merchant' => $user,
                ];

                $response = ResponseStd::okSingle($dataRresponse);
            }
        }
        return $response;
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  array $data
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return void
     * @throws ValidationException
     */
    public function validate($data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new ValidationException($validator,
                new JsonResponse($this->formatValidationErrors($validator), 422)
            );
        }
    }

    public function revokeToken(Request $request)
    {
        $user = Auth::guard('merchants')->user();
        if ($user === null) {
            return ResponseStd::fail('user not logged in', 401);
        }
        $accessToken = $user->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true,
            ]);
        $accessToken->revoke();
        event(new EventAuthLogout(Auth::guard('merchants'), $user));

        return ResponseStd::okSingle('ok');
    }

    /**
     * Format the validation errors to be returned.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return array
     */
    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->getMessages();
    }
}
