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

use Illuminate\Support\Str;

/**
 * Class NumberLibrary.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Libraries
 */
class NumberLibrary
{
    /**
     * Convert decimal value to roman number.
     *
     * @param $value
     * @return bool|mixed
     */
    public static function decimalToRomawi($value)
    {
        if (!is_numeric($value) || $value > 3999 || $value <= 0) {
            return false;
        }
        $roman = array('M' => 1000, 'D' => 500, 'C' => 100, 'L' => 50, 'X' => 10, 'V' => 5, 'I' => 1);
        foreach ($roman as $k => $v) {
            if (($amount[$k] = floor($value / $v)) > 0) {
                $value -= $amount[$k] * $v;
            }
        }
        $return = '';
        foreach ($amount as $k => $v) {
            $old_k = $k;
            $return .= $v <= 3 ? str_repeat($k, $v) : $k . $old_k;
        }
        return str_replace(array('VIV', 'LXL', 'DCD'), array('IX', 'XC', 'CM'), $return);
    }

    /**
     * @param $name
     * @return string
     */
    public static function randomName($name)
    {
        $slugName = Str::slug($name);
        $slugName = self::cleanString($slugName);

        $timeRandom = self::microTimeStamp();
        $random = substr(md5($timeRandom), 6, 8);
        $nameSubstr = substr($slugName, 0, 6);
        $output = $nameSubstr . $random;

        return Str::upper(str_replace('-', '', $output));
    }

    /**
     * Hapus karakter i,o,l,u,v.
     *
     * @param $string
     * @return null|string|string[]
     */
    public static function cleanString($string)
    {
        $replace = preg_replace('/[^A-Za-z0-9. -]/', '', $string);
        $replace = preg_replace('/[i]/', 'z', $replace);
        $replace = preg_replace('/[I]/', 'h', $replace);
        $replace = preg_replace('/[O]/', 'k', $replace);
        $replace = preg_replace('/[1]/', 'j', $replace);
        $replace = preg_replace('/[o]/', 'k', $replace);
        $replace = preg_replace('/[0]/', 'a', $replace);
        $replace = preg_replace('/[l]/', 't', $replace);
        $replace = preg_replace('/[v]/', 'r', $replace);
        $replace = preg_replace('/[u]/', 'm', $replace);

        // return
        return $replace;
    }

    /**
     * Parse 1.000.000,00 to 1000000.
     *
     * @param $string_money
     * @param string $separator
     * @return float|int
     */
    public static function moneyToNumber($string_money, $separator = '.')
    {
        $_val = str_replace($separator, "", $string_money);
        if (is_nan($_val)) {
            $r = 0;
        } else {
            $r = (float)$_val;
        }
        return $r;
    }

    /**
     * Generates and returns a string of digits representing the time of the
     * current system in microseconds granularity.
     *
     * Compared to the standard time() function, the microtime() function is more
     * accurate and in addition, successive quick calls inside a loop generate
     * unique results; which can be quite useful in certain cases.
     *
     * Our function below generates digits only output based on the time stamp
     * generated by the microtime() function.
     *
     * @return string
     */
    public static function microTimeStamp()
    {
        $mt = microtime();
        $r = "";
        $length = strlen($mt);
        for ($i = 0; $i < $length; $i++) {
            if (ctype_digit($mt[$i])) {
                $r .= $mt[$i];
            }
        }
        return $r;
    }

    public static function removeSpecialChar($value)
    {
        $result = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $value);
        return $result;
    }

    /**
     * Create invoice number.
     *
     * @return string
     */
    public static function createInvoice()
    {
        $date = date('Y') . date('m') . date('d');
        $romawiDate = \App\Libraries\NumberLibrary::decimalToRomawi(date('d'));
        $romawiMonth = \App\Libraries\NumberLibrary::decimalToRomawi(date('m'));
        $uniqueNumber = \App\Libraries\SecurityLibrary::generateSign();
        return 'INV' . '/' . $date . '/' . $romawiDate . '/' . $romawiMonth . '/' . $uniqueNumber;
    }

    public static function asJSON($data)
    {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);

        return $json;
    }

    public static function asString($data)
    {
        $json = self::asJSON($data);

        return wordwrap($json, 76, "\n   ");
    }
}
