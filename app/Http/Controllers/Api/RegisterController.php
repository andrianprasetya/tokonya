<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Libraries\ResponseStd;
use App\Mail\VerificationEmail;
use App\Models\Constants;
use App\Rules\OnlyVerifiedMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

/**
 * Class RegisterController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Http\Controllers\Api
 */
class RegisterController extends BaseApi
{
    protected function validateRegister(array $data)
    {
        $arrayValidator = [
            'email' => ['required', 'email', 'min:3', 'max:80', 'unique:users,email,NULL,id', new OnlyVerifiedMail],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'min:5'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    /**
     * Register an user.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateRegister($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $user = new User();
            $userId = Uuid::uuid4()->toString();
            $user->id = $userId;
            $user->role_id = Constants::DEFAULT_ROLE_USER;
            $user->email = $request->input('email');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->join_date = Carbon::now();
            $user->created_at = time();
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            //Send invitations.
            if (app()->environment('production')) {
                $user->is_active = 0;
                \Mail::to($user->email)->send(new VerificationEmail($user));
                //$user->sendEmailVerificationNotification();
            } else {
                $user->is_active = 1;
                $user->verified_at = time();
            }

            // Save
            $user->save();
            DB::commit();

            // return successful response
            return ResponseStd::okSingle($user);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                return ResponseStd::fail($e->getMessage());
            }
        }
    }
}