<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


/**
 * App\Models\TwoFactorRecovery
 *
 * @property int $id
 * @property string $code
 * @property bool $used
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|TwoFactorRecovery newModelQuery()
 * @method static Builder|TwoFactorRecovery newQuery()
 * @method static \Illuminate\Database\Query\Builder|TwoFactorRecovery onlyTrashed()
 * @method static Builder|TwoFactorRecovery query()
 * @method static Builder|TwoFactorRecovery whereCode($value)
 * @method static Builder|TwoFactorRecovery whereCreatedAt($value)
 * @method static Builder|TwoFactorRecovery whereDeletedAt($value)
 * @method static Builder|TwoFactorRecovery whereId($value)
 * @method static Builder|TwoFactorRecovery whereUpdatedAt($value)
 * @method static Builder|TwoFactorRecovery whereUsed($value)
 * @method static Builder|TwoFactorRecovery whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|TwoFactorRecovery withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TwoFactorRecovery withoutTrashed()
 * @mixin Eloquent
 */
class TwoFactorRecovery extends Model
{
    use SoftDeletes;

    protected $table = 'two_factor_recovery_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'used', 'code', 'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'used' => 'boolean'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'used' => false
    ];
}
