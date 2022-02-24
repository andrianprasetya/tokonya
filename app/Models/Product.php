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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Product Model.
 *
 * @package App\Models
 */
class Product extends Model
{
    use SoftDeletes;

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
    public $table = 'products';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'brand_id',
        'sku',
        'product_name',
        'product_price',
        'product_description',
        'product_rating',
        'category_code',
        'category_id',
        'merchant_code',
        'merchant_id',
        'stock',
        'subtract',
        'is_pre_order',
        'pre_order_period',
        'pre_order_length',
        'is_active',
        'weight',
        'weight_length',
        'dimension_width',
        'dimension_height',
        'dimension_length',
        'image_id',
        'image_id2',
        'image_id3',
        'image_id4',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relation to image Sample usage.
     *
     * <code>
     * $product->image->file_url
     * </code>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(FileModel::class, 'image_id', 'id');
    }

    public function image2()
    {
        return $this->belongsTo(FileModel::class, 'image_id2', 'id');
    }

    public function image3()
    {
        return $this->belongsTo(FileModel::class, 'image_id3', 'id');
    }

    public function image4()
    {
        return $this->belongsTo(FileModel::class, 'image_id4', 'id');
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

    /**
     * Relation to rack Sample usage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rack()
    {
        return $this->hasMany(ProductHasRack::class, 'product_id', 'id');
    }
}
