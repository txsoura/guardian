<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $model
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static \Illuminate\Database\Query\Builder|Permission onlyTrashed()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereDeletedAt($value)
 * @method static Builder|Permission whereDescription($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereModel($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Permission withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Permission withoutTrashed()
 * @mixin Eloquent
 */
class Permission extends Model
{
    use  SoftDeletes;

    protected $table = 'acl_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'model', 'description'
    ];
}
