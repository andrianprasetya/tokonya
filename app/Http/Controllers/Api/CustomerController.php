<?php

/**
 * Copyright 2021 Odenktools Technology Open Source Project
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
use App\Http\Resources\CustomerResource;
use App\Libraries\FilesLibrary;
use App\Libraries\MerchantLibrary;
use App\Libraries\ResponseStd;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Client;

/**
 * Class CustomerController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Http\Controllers\Api
 */
class CustomerController extends BaseApi
{
    public function showProfile()
    {
        DB::beginTransaction();
        try {
            $customer = auth()->user();
            if (!$customer) {
                throw new \Exception("Invalid customer.");
            }
            // return response.
            $single = new CustomerResource($customer);

            return ResponseStd::okSingle($single);
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

    protected function validateUpdate(array $data)
    {
        $arrayValidator = [
            'gender' => ['required', 'in:female,male,other'],
            'customer_address' => ['nullable'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateUpdate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = auth()->user();
            if (!$model) {
                throw new \Exception("Invalid customer");
            }
            $data = Customer::query()->find($model->id);

            foreach ($request->file() as $key => $file) {
                if ($request->hasFile($key)) {
                    if ($request->file($key)->isValid()) {
                        $imageId = (new FilesLibrary())
                            ->saveImage($request->file($key),
                                'images/customer',
                                false,
                                300,
                                300,
                                'customer');
                        $hasImage = !empty($model->image_id) ? true : false;
                        // delete physical image
                        if ($hasImage) {
                            $fileId = $model->image_id;
                            $results = DB::select(DB::raw("SELECT file_url FROM files WHERE id = '$fileId'"));
                            Storage::disk()->delete($results[0]->file_url);
                        }
                        $data->image_id = $imageId;
                    }
                }
            }

            $data->customer_address = $request->input('customer_address')
                ? $request->input('customer_address') : null;

            $data->gender = $request->input('gender');

            $data->save();

            DB::commit();

            // return response.
            $single = new CustomerResource($data);

            return ResponseStd::okSingle($single);
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

    protected function validateMerchantCreate(array $data)
    {
        $arrayValidator = [
            'merchant_name' => ['required', 'min:5', 'max:60'],
            'merchant_address' => ['required'],
            'password' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    public function createMerchant(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateMerchantCreate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = auth()->user();
            if (!$model) {
                throw new \Exception("Invalid customer");
            }
            $customer = Customer::query()->find($model->id);

            $hash = Hash::check($request->input('password'), $customer->password, []);

            if (!$hash) {
                throw new \Exception("invalid password");
            }

            $merchant = MerchantLibrary::createMerchantCustomer($customer,
                $request->input('merchant_name'),
                $request->input('merchant_address')
            );

            $oauthTable = Client::query()
                ->where('provider', 'merchants')->first();

            if (!$oauthTable) {
                throw new \Exception("invalid password");
            }

            $params = [
                'username' => $merchant->email,
                'password' => $request->input('password'),
                'client_id' => $oauthTable->id,
                'client_secret' => $oauthTable->secret,
                'grant_type' => 'password',
            ];

            $requestLogin = Request::create(route('api::merchant.login'), 'POST', $params);
            $sendLogin = app()->handle($requestLogin);
            $content = json_decode($sendLogin->getContent());
            if ($sendLogin->getStatusCode() !== 200) {
                throw new \Exception("invalid login.");
            }
            DB::commit();

            return ResponseStd::okSingle($content->data->item);
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
