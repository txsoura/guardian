<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Admin',
            'guard_name' => 'admin',
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'status' => UserStatus::APPROVED,
            'role' => 1
        ]);

        if (App::environment('local', 'staging')) {
            \App\Models\User::factory(10)->create();
        }
    }
}
