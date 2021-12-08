<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Http\Resources\CustomerResource;
use App\Libraries\FilesLibrary;
use App\Libraries\ResponseStd;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
                return ResponseStd::fail($e->getMessage());
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

            $dataImageId = null;
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
                        $dataImageId = $imageId;
                    }
                }
            }

            $data->update([
                'customer_address' => $request->input('customer_address')
                    ? $request->input('customer_address') : null,
                'gender' => $request->input('gender'),
                'image_id' => $dataImageId,
            ]);

            DB::commit();

            // return response.
            $single = new CustomerResource($data);

            return ResponseStd::okSingle($single);
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