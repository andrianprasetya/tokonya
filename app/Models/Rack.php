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

class Rack extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'racks';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'merchant_id',
        'rack_name',
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_has_racks')
            ->using(ProductHasRack::class)
            ->withPivot('id');
    }

    /**
     * Formatting Date.
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        if ($this->attributes['created_at'] === null) {
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