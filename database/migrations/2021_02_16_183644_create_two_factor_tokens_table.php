<?php

use App\Enums\TwoFactorProvider;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoFactorTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_factor_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('provider', TwoFactorProvider::toArray());
            $table->string('code');
            $table->timestamp('expiration');
            $table->boolean('used')->default(false);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('two_factor_tokens');
    }
}
