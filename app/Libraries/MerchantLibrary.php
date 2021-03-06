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

namespace App\Libraries;

use App\Models\Customer;
use App\Models\Merchant;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * Class MerchantLibrary.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Libraries
 */
class MerchantLibrary
{
    /**
     * Create merchant.
     *
     * @param $email
     * @param $name
     * @param $address
     * @param $password
     * @param $merchantId
     * @param int $verified
     * @param int $isActive
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private static function createMerchant(
        $email,
        $name,
        $address,
        $password,
        $verified = 0,
        $isActive = 0
    ) {
        $code = NumberLibrary::randomName($name);
        $verifiedAt = null;
        if ($verified == 1) {
            $verifiedAt = Carbon::now();
        }
        $model = Merchant::query()->forceCreate([
            'id' => Uuid::uuid4()->toString(),
            'email' => $email,
            'merchant_name' => $name,
            'merchant_code' => $code,
            'merchant_address' => $address,
            'image_id' => null,
            'password' => $password,
            'is_active' => $isActive,
            'join_date' => Carbon::now(),
            'created_at' => Carbon::now(),
            'verified_at' => $verifiedAt,
        ]);

        return $model;
    }

    /**
     * Create merchant from existing customer.
     *
     * @param @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null $customer
     * @param $merchantName
     * @param $merchantAddress
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function createMerchantCustomer(Customer $customer, $merchantName, $merchantAddress)
    {
        // create a new merchant from customer.
        $merchant = self::createMerchant($customer->email, $merchantName, $merchantAddress, $customer->password, 1, 1);

        // Update a customer.
        $customerModel = Customer::query()->find($customer->id);
        $customerModel->update([
            'merchant_id' => $merchant->id
        ]);

        return $merchant;
    }
}
