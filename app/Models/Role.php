<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable, SoftDeletes;

    protected $table = 'acl_roles';
    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'id', 'acl_role_id');
    }
}
