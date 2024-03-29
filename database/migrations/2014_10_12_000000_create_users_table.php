<?php

use App\Enums\TwoFactorProvider;
use App\Enums\UserLang;
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
            $table->string('avatar')->nullable();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('cellphone')->nullable();
            $table->timestamp('cellphone_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('status', UserStatus::toArray())->default(UserStatus::PENDENT);
            $table->foreignId('role_id')->references('id')->on('acl_roles');
            $table->enum('two_factor_provider', TwoFactorProvider::toArray())->nullable();
            $table->string('fcm_token')->nullable();
            $table->enum('lang', UserLang::toArray())->default(UserLang::PT);
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
