<?php

use App\Enums\TwoFactorProvider;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('cellphone')->unique()->nullable();
            $table->timestamp('cellphone_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('status', UserStatus::toArray())->default(UserStatus::PENDENT);
            $table->foreignId('role_id')->references('id')->on('acl_roles');
            $table->enum('two_factor_provider', TwoFactorProvider::toArray())->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
