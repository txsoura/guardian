<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\AccessToken
 *
 * @property int $id
 * @property string $name
 * @property string $token
 * @property array $abilities
 * @property Carbon|null $last_used_at
 * @property Carbon|null $expiration
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|AccessToken newModelQuery()
 * @method static Builder|AccessToken newQuery()
 * @method static \Illuminate\Database\Query\Builder|AccessToken onlyTrashed()
 * @method static Builder|AccessToken query()
 * @method static Builder|AccessToken whereAbilities($value)
 * @method static Builder|AccessToken whereCreatedAt($value)
 * @method static Builder|AccessToken whereDeletedAt($value)
 * @method static Builder|AccessToken whereExpiration($value)
 * @method static Builder|AccessToken whereId($value)
 * @method static Builder|AccessToken whereLastUsedAt($value)
 * @method static Builder|AccessToken whereName($value)
 * @method static Builder|AccessToken whereToken($value)
 * @method static Builder|AccessToken whereUpdatedAt($value)
 * @method static Builder|AccessToken whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|AccessToken withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AccessToken withoutTrashed()
 * @mixin Eloquent
 */
class AccessToken extends Model
{
    use SoftDeletes;

    protected $table = 'access_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'token', 'expiration', 'last_used_at', 'user_id', 'abilities'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiration' => 'datetime',
        'last_used_at' => 'datetime',
        'abilities' => 'json'
    ];
}
