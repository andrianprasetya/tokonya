<?php

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
            $customer = Customer::find($request->id);
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
