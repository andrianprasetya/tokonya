<?php

namespace App\Models;

use App\User;
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
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

    public function menus()
    {
        return $this->belongsToMany(Menu::class,
            'menu_roles',
            'role_id',
            'menu_id');
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
