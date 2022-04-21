<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\Models\RolePermission
 *
 * @property int $acl_role_id
 * @property int $acl_permission_id
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Permission $permissions
 * @method static Builder|RolePermission newModelQuery()
 * @method static Builder|RolePermission newQuery()
 * @method static Builder|RolePermission query()
 * @method static Builder|RolePermission whereAclPermissionId($value)
 * @method static Builder|RolePermission whereAclRoleId($value)
 * @mixin Eloquent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RolePermission whereCreatedAt($value)
 * @method static Builder|RolePermission whereId($value)
 * @method static Builder|RolePermission whereUpdatedAt($value)
 */
class RolePermission extends Model
{
    use Notifiable;

    protected $table = 'acl_role_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acl_role_id', 'acl_permission_id'
    ];


    public function permissions()
    {
        return $this->belongsTo( Permission::class,'acl_permission_id','id');
    }
}
