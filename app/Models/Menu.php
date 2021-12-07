<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Models
 */
class Menu extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'menus';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'parent_id',
        'menu_title',
        'slug',
        'url',
        'icon',
        'menu_order',
        'is_active',
        'is_default'
    ];

    public static function adminSidebar($withRole = true)
    {
        $defaultMenuRoot = Constants::DEFAULT_SIDEBAR;
        if ($withRole) {
            return self::select('*')->where('parent_id', $defaultMenuRoot)
                ->with(['children'])
                ->orderBy('menu_order', 'asc');
        } else {
            return self::select('*')->where('parent_id', $defaultMenuRoot)
                ->with(['children'])
                ->orderBy('menu_order', 'asc');
        }
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_roles',
            'menu_id', 'role_id')
            ->using(MenuRole::class)
            ->withPivot('is_enabled', 'access');
    }

    public function menu_roles()
    {
        return $this->hasMany(MenuRole::class);
    }

    public function findBySlug(string $roleName, $slugName = null)
    {
        $role = static::where('role_name', $roleName)->where('slug', $slugName)->first();
        return $role;
    }

    /**
     * Get the parent that owns the node.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get value of the model parent_id column.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->getAttribute('parent_id');
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
