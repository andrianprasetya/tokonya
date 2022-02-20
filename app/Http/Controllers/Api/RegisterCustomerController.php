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
use App\Libraries\ResponseStd;
use App\Models\Customer;
use App\Notifications\VerificationUserNotify;
use App\Rules\OnlyVerifiedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\QueryException;

class RegisterCustomerController extends BaseApi
{
    protected function validateRegister(array $data)
    {
        $arrayValidator = [
            'email' => ['required', 'email', 'min:3', 'max:80', 'unique:customers,email,NULL,id', new OnlyVerifiedMail],
            'customer_name' => ['required', 'min:5', 'max:60'],
            'password' => ['required', 'min:5'],
            'gender' => ['required', 'in:female,male,other'],
            'customer_address' => ['nullable'],
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
            $customerName = $request->input('customer_name');
            $customerEmail = $request->input('email');
            $customerCode = \App\Libraries\NumberLibrary::randomName($customerName);
            $customer = Customer::query()->create([
                'id' => Uuid::uuid4()->toString(),
                'email' => $customerEmail,
                'customer_code' => $customerCode,
                'customer_name' => $customerName,
                'gender' => $request->input('gender'),
                'customer_address' => $request->input('customer_address') !== null
                || $request->has('customer_address') ? $request->input('customer_address') : null,
                'join_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'password' => app('hash')->make($request->input('password')),
                'is_active' => app()->environment('production') ? 0 : 1,
                'verified_at' => app()->environment('production') ? null : Carbon::now(),
            ]);

            //Send invitations.
            if (app()->environment('production')) {
                Notification::send($customer, new VerificationUserNotify($customer));
            }

            DB::commit();

            // return successful response
            return ResponseStd::okSingle($customer);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }
}
