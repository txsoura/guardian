<?php

namespace App\Models;

use App\Enums\UserLang;
use App\Enums\UserStatus;
use App\Http\Helpers\TwilioHelper;
use App\Notifications\ResetPassword;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $avatar
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property int|null $cellphone
 * @property Carbon|null $cellphone_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string $status
 * @property string $lang
 * @property int $role_id
 * @property string|null $two_factor_provider
 * @property string|null $fcm_token
 * @property string|null $totp_secret
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|RolePermission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Role|null $role
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCellphone($value)
 * @method static Builder|User whereCellphoneVerifiedAt($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFcmToken($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLang($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereTotpSecret($value)
 * @method static Builder|User whereTwoFactorProvider($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail, HasLocalePreference
{
    use Notifiable, SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role_id', 'fcm_token', 'lang' , 'status', 'password'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['email_verified_at', 'cellphone_verified_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'totp_secret'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'cellphone_verified_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => UserStatus::PENDENT,
        'lang' => UserLang::PT
    ];

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale(): string
    {
        return $this->lang;
    }

    /**
     * Send email verification notification.
     *
     * @return void
     * @throws ConfigurationException|TwilioException
     */
    public function sendEmailVerificationNotification()
    {
        TwilioHelper::verify()
            ->verifications
            ->create($this->email, 'email');
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Check if cellphone is verified.
     *
     * @return bool
     */
    public function hasVerifiedCellphone(): bool
    {
        if ($this->cellphone_verified_at) {
            return true;
        }

        return false;
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'verified' => (bool)$this->email_verified_at,
            'role' => $this->role->name,
            'status' => $this->status
        ];
    }

    /**
     * Encrypt the user's totp secret.
     *
     * @param string $value
     */
    public function setTotpSecret(string $value)
    {
        $this->totp_secret = encrypt($value);
    }

    /**
     * Decrypt the user's totp secret.
     *
     * @return string
     */
    public function getTotpSecret(): string
    {
        return decrypt($this->totp_secret);
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'acl_role_id', 'role_id');
    }
}
