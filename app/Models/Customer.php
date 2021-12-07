<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Traits\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * Customer Model.
 *
 * @package App\Models
 */
class Customer extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, HasApiTokens, MustVerifyEmail;

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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'customer_code',
        'customer_name',
        'email',
        'image_id',
        'is_active',
        'join_date',
        'verified_at',
    ];

    public $table = 'customers';

    /**
     * [OVERRIDE].
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function image()
    {
        return $this->belongsTo(FileModel::class, 'image_id', 'id');
    }

    /**
     * Formatting Date.
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
     * Formatting Date.
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

    public function findForPassport($identifier)
    {
        return $this
            ->where('is_active', '=', 1)
            ->where('email', $identifier)
            ->first();
    }

    /**
     * @return boolean
     */
    public function isActivated()
    {
        if ($this->is_active == 1) {
            return true;
        }
        return false;
    }
}
