<?php

namespace App\Repositories;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Txsoura\Core\Repositories\CoreRepository;

class UserRepository extends CoreRepository
{
    /**
     * Allow model relations to use in include
     * @var array
     */
    protected $possibleRelationships = ['role', 'permissions'];
    /**
     * Allowed model columns to use in conditional query
     * @var array
     */
    protected $allow_where = array('email', 'status', 'name', 'cellphone');
    /**
     * Allowed model columns to use in sort
     * @var array
     */
    protected $allow_order = array('email', 'status', 'name', 'cellphone', 'created_at', 'updated_at');
    /**
     * Allowed model columns to use in query search
     * @var array
     */
    protected $allow_like = array('email', 'name', 'cellphone');
    /**
     * Allowed model columns to use in filter by date
     * @var array
     */
    protected $allow_between_dates = array('created_at', 'updated_at');
    /**
     * Allowed model columns to use in filter by value
     * @var array
     */
    protected $allow_between_values = array();

    /**
     * @param User $user
     * @return User|null
     */
    public function approve(User $user): ?User
    {
        $user->status = UserStatus::APPROVED;
        $user->update();

        return $user;
    }

    /**
     * @param User $user
     * @return User|null
     */
    public function block(User $user): ?User
    {
        $user->status = UserStatus::BLOCKED;
        $user->update();

        return $user;
    }

    /**
     * @param User $user
     * @param String $email
     * @return User|null
     */
    public function email(User $user, string $email): ?User
    {
        $user->email = $email;
        $user->email_verified_at = null;
        $user->update();

        return $user;
    }

    /**
     * @param User $user
     * @param int $cellphone
     * @return User|null
     */
    public function cellphone(User $user, int $cellphone): ?User
    {
        $user->cellphone = $cellphone;
        $user->cellphone_verified_at = null;
        $user->update();

        return $user;
    }

    /**
     * @param User $user
     * @param String $password
     */
    public function password(User $user, string $password)
    {
        $user->password = Hash::make($password);
        $user->update();
    }

    protected function model(): string
    {
        return User::class;
    }
}
