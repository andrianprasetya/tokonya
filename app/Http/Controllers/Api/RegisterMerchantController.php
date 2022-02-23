<?php

/**
 * Copyright 2022 Odenktools Technology Open Source Project
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Libraries\NumberLibrary;
use App\Libraries\ResponseStd;
use App\Models\Merchant;
use App\Notifications\VerificationMerchantNotify;
use App\Rules\OnlyVerifiedMail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class RegisterMerchantController extends BaseApi
{
    protected function validateRegister(array $data)
    {
        $arrayValidator = [
            'email' => ['required', 'email', 'min:3', 'max:80', 'unique:merchants,email,NULL,id', new OnlyVerifiedMail],
            'merchant_name' => ['required', 'min:5', 'max:60'],
            'password' => ['required', 'min:5'],
            'merchant_address' => ['required', 'min:5'],
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
            $merchantName = $request->input('merchant_name');
            $merchantEmail = $request->input('email');
            $merchant = Merchant::query()->create([
                'id' => Uuid::uuid4()->toString(),
                'email' => $merchantEmail,
                'merchant_code' => NumberLibrary::randomName($merchantName),
                'merchant_name' => $merchantName,
                'merchant_address' => $request->input('merchant_address'),
                'join_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'password' => app('hash')->make($request->input('password')),
                'is_active' => app()->environment('production') ? 0 : 1,
                'verified_at' => app()->environment('production') ? null : Carbon::now(),
            ]);

            //Send invitations.
            if (app()->environment('production')) {
                Notification::send($merchant, new VerificationMerchantNotify($merchant));
            }

            DB::commit();

            // return successful response
            return ResponseStd::okSingle($merchant);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }
}
