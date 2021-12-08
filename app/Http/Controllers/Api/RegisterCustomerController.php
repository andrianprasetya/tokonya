<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Libraries\ResponseStd;
use App\Mail\VerificationEmail;
use App\Models\Customer;
use App\Rules\OnlyVerifiedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

/**
 * Class RegisterCustomerController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Http\Controllers\Api
 */
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
                Mail::to($customerEmail)->send(new VerificationEmail($customer));
            }

            DB::commit();

            // return successful response
            return ResponseStd::okSingle($customer);
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