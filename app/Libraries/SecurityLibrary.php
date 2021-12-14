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

use Ramsey\Uuid\Uuid;

class SecurityLibrary
{
    public static function generateSign($hmac = false)
    {
        $string_to_sign = Uuid::uuid4()->toString();
        $auth_secret = Uuid::uuid4()->toString();
        $auth_signature = base64_encode(hash_hmac('sha256', $string_to_sign, $auth_secret, false));
        if ($hmac == true) {
            $otpString = hash_hmac('sha256', $string_to_sign, $auth_secret, false);
        } else {
            $otpString = self::getRfc($auth_signature, strtotime("now"), 10);
        }
        return $otpString;
    }

    /**
     * This function implements the algorithm outlined
     * in RFC 6238 for Time-Based One-Time Passwords
     *
     * @link http://tools.ietf.org/html/rfc6238
     * @param string $key the string to use for the HMAC key
     * @param mixed $time a value that reflects a time (unix
     *                       time in the example)
     * @param int $digits the desired length of the OTP
     * @param string $crypto the desired HMAC crypto algorithm
     * @return string the generated OTP
     */
    private static function getRfc($key, $time, $digits = 8, $crypto = 'sha256')
    {
        $digits = intval($digits);
        $result = null;

        // Convert counter to binary (64-bit)
        $data = pack('NNC*', $time >> 32, $time & 0xFFFFFFFF);

        // Pad to 8 chars (if necessary)
        if (strlen($data) < 8) {
            $data = str_pad($data, 8, chr(0), STR_PAD_LEFT);
        }

        // Get the hash
        $hash = hash_hmac($crypto, $data, $key);

        // Grab the offset
        $offset = 2 * hexdec(substr($hash, strlen($hash) - 1, 1));

        // Grab the portion we're interested in
        $binary = hexdec(substr($hash, $offset, 8)) & 0x7fffffff;

        // Modulus
        $result = $binary % pow(10, $digits);

        // Pad (if necessary)
        $result = str_pad($result, $digits, "0", STR_PAD_LEFT);

        return $result;
    }
}
