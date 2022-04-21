<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->users();

        if (App::environment('local')) {
            User::factory(1)->create();
        }
    }

    public function users()
    {
        $env = App::environment();

        User::firstOrCreate(
            [
                'email' => App::environment('staging') ? 'sandbox.admin@txsoura.com' : 'admin@txsoura.com',
            ],
            [
                'name' => 'Guardian Admin',
                'email_verified_at' => now(),
                'password' => 'APa$$w0rd1' . $env[0],
                'status' => UserStatus::APPROVED,
                'role_id' => 1
            ]
        );

        User::firstOrCreate(
            [
                'email' => App::environment('staging') ? 'sandbox.user@txsoura.com' : 'user@txsoura.com',
            ],
            [
                'name' => 'Guardian User',
                'email_verified_at' => now(),
                'password' => 'CPa$$w0rd2' . $env[0],
                'status' => UserStatus::APPROVED,
                'role_id' => 2
            ]
        );
    }
}
