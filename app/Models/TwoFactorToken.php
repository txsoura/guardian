<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorToken extends Model
{
    protected $table = 'two_factor_tokens';
    protected $primaryKey = 'id';
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider', 'used', 'expiration', 'code', 'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expiration' => 'datetime',
        'used' => 'boolean',
    ];
}
