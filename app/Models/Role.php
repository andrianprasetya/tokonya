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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Role Model.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Models
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class Role extends Model
{

    public $table = 'roles';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     * @var string
     */
    public $keyType = 'string';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'slug',
        'role_name',
        'role_desc',
        'is_active',
        'is_default',
        'created_at',
        'updated_at'
    ];

    public static function sql()
    {
        return self::select(
            'roles.id',
            'roles.slug',
            'roles.role_name',
            'roles.role_desc',
            'roles.is_active',
            'roles.is_default',
            'roles.created_at',
            'roles.updated_at'
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,
            'permission_roles',
            'role_id',
            'permission_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function findBySlug($slugName)
    {
        $role = static::where('slug', $slugName)->first();
        return $role;
    }

    /**
     * Formatting Date.
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        if ($this->attributes['updated_at'] === null) {
            return null;
        }

        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
    }

    /**
     * Formatting Date.
     *
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        if ($this->attributes['updated_at'] === null) {
            return null;
        }

        return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
    }
}
