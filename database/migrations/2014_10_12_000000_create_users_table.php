<?php

use App\Enums\Sex;
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
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('status', UserStatus::toArray())->default(UserStatus::PENDENT);
            $table->foreignId('role_id')->references('id')->on('acl_roles');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users');
            $table->primary('user_id');
            $table->string('img')->nullable();
            $table->enum('sex', Sex::toArray())->nullable();
            $table->date('birthdate')->nullable();
            $table->bigInteger('cellphone')->nullable();
            $table->string('name')->nullable();
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
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('users');
    }
}
