<?php

/**
 * Copyright 2021 Odenktools Technology Open Source Project
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
 * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
 * OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace App\Models;

/**
 * Dont modify anything without any permission from author, it can make system damage.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Models
 */
class Constants
{
    public const DEFAULT_SIDEBAR = '386a3745-3c13-58c4-f6ac-c1962cabc9db';

    // DEFAULT ROLES IN APP
    public const DEFAULT_ROLE_OWNER = '2fba941d-8e21-40f0-829b-76087e1618d3';
    public const DEFAULT_ROLE_ADMINISTRATOR = '2bac951e-6f32-41e2-817c-61084f2768b2';
    public const DEFAULT_ROLE_CONTRIBUTOR = '2bac951e-6f32-41e2-817c-61084f2768b3';
    public const DEFAULT_ROLE_USER = '2bac951e-6f32-41e2-817c-61084f2768b4';

    public const DEFAULT_ROLES = [
        ['id' => '2fba941d-8e21-40f0-829b-76087e1618d3', 'slug' => 'owner', 'role_name' => 'Owner'],
        ['id' => '2bac951e-6f32-41e2-817c-61084f2768b23', 'slug' => 'administrator', 'role_name' => 'Administrator'],
        ['id' => '2bac951e-6f32-41e2-817c-61084f2768b3', 'slug' => 'contributor', 'role_name' => 'Contributor'],
        ['id' => '2bac951e-6f32-41e2-817c-61084f2768b4', 'slug' => 'user', 'role_name' => 'User'],
    ];

    public static function searchById($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return $val;
            }
        }
        return null;
    }

    public static function searchBySlug($slug, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['slug'] === $slug) {
                return $val;
            }
        }
        return null;
    }
}
