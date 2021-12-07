<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Category Model.
 *
 * @package App\Models
 */
class Category extends Model
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
    public $table = 'categories';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

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
        'category_code',
        'category_name',
        'category_description',
        'is_active',
        'image_id',
        'parent_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relation to image Sample usage.
     *
     * <code>
     * $cateogry->image->file_url
     * </code>
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(FileModel::class, 'image_id', 'id');
    }

    /**
     * Formatting Tanggal.
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        $timeZone = optional(auth()->user())->timezone ?? config('app.APP_TIMEZONE');
        return Carbon::createFromTimestamp($this->attributes['created_at'], $timeZone)
            ->format('Y-m-d H:i:s');
    }

    /**
     * Formatting Tanggal.
     *
     * @return string
     */
    public function getUpdatedAtAttribute()
    {
        if ($this->attributes['updated_at'] === null) {
            return null;
        }
        $timeZone = optional(auth()->user())->timezone ?? config('app.APP_TIMEZONE');
        return Carbon::createFromTimestamp($this->attributes['updated_at'], $timeZone)
            ->format('Y-m-d H:i:s');
    }
}
