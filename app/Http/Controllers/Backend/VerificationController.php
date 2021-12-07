<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libraries\ResponseStd;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

/**
 * Class VerificationController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Http\Controllers\Backend
 */
class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        try {
            $user = User::find($request->id);
            if (!$user) {
                throw new \Exception("verification not valid.");
            }
            //Cek jika user sudah terverifikasi.
            if ($user->hasVerifiedEmail()) {
                throw new \Exception("verification not valid.");
            }
            if ($request->hash === sha1(substr($user->id, 4, 6) . $user->email)) {
                $time = Carbon::now();
                if ($user->markEmailAsVerified($time)) {
                    event(new Verified($user));
                }
            } else {
                throw new \Exception("verification not valid.");
            }
        } catch (\Exception $e) {
            return ResponseStd::fail($e->getMessage());
        }
    }
}