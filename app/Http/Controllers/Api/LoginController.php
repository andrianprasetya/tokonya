<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Libraries\LoginProxy;
use App\Libraries\ResponseStd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Http\Controllers\Api
 */
class LoginController extends BaseApi
{
    private $loginProxy;

    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
    }

    protected function validateLogin(array $data)
    {
        $arrayValidator = [
            'email' => ['required', 'email', 'max:80'],
            'password' => ['required', 'min:5'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    protected function validateRefresh(array $data)
    {
        $arrayValidator = [
            'refresh_token' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    public function login(Request $request)
    {
        try {
            $validate = $this->validateLogin($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $email = $request->input('email');
            $password = $request->input('password');
            $loginRequest = [
                'username' => $email,
                'password' => $password,
            ];
            $data = $this->loginProxy->attemptLogin($loginRequest);
            return response()->json($data->getData(), $data->getStatusCode());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseStd::fail($e->getMessage());
        }
    }

    public function refresh(Request $request)
    {
        try {
            $validate = $this->validateRefresh($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $refreshToken = $request->input('refresh_token');
            $data = $this->loginProxy->attemptRefresh($refreshToken);
            return response()->json($data->getData(), $data->getStatusCode());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }

    public function logout()
    {
        $this->loginProxy->logout();
        return response()->json(['message' => 'ok'], 200);
    }
}
