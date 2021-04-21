<?php

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Txsoura Guardian Admin User',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'cellphone' => '12345678',
            'cellphone_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'status' => UserStatus::APPROVED,
            'role_id' => 1
        ]);
    }
}
