<?php

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
        array('id' => '2fba941d-8e21-40f0-829b-76087e1618d3', 'slug' => 'owner', 'role_name' => 'Owner'),
        array('id' => '2bac951e-6f32-41e2-817c-61084f2768b23', 'slug' => 'administrator', 'role_name' => 'Administrator'),
        array('id' => '2bac951e-6f32-41e2-817c-61084f2768b3', 'slug' => 'contributor', 'role_name' => 'Contributor'),
        array('id' => '2bac951e-6f32-41e2-817c-61084f2768b4', 'slug' => 'user', 'role_name' => 'User'),
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
