<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class RolePermission extends Model
{
    use Notifiable;

    protected $table = 'acl_role_permissions';
    protected $primaryKey = 'role_id';
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acl_role_id', 'acl_permission_id'
    ];
}
