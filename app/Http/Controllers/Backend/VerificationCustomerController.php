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

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libraries\ResponseStd;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

/**
 * Class VerificationController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Http\Controllers\Backend
 */
class VerificationCustomerController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $customer = Customer::query()->find($request->id);
            if (!$customer) {
                throw new \Exception("verification not valid.");
            }
            //Check customer is verified?.
            if ($customer->hasVerifiedEmail()) {
                throw new \Exception("verification not valid.");
            }
            if ($request->hash === sha1(substr($customer->id, 4, 6) . $customer->email)) {
                $time = Carbon::now();
                // set customer verified status.
                if ($customer->markEmailAsVerified($time)) {
                    event(new Verified($customer));
                }
            } else {
                throw new \Exception("verification not valid.");
            }
        } catch (\Exception $e) {
            return ResponseStd::fail($e->getMessage());
        }
    }
}
