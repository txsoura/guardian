<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuardianAclTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('acl_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('model');
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('acl_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('acl_role_permissions', function (Blueprint $table) {
            $table->foreignId('acl_role_id')->references('id')->on('acl_roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('acl_permission_id')->references('id')->on('acl_permissions')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['acl_permission_id', 'acl_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('acl_role_permissions');
        Schema::drop('acl_roles');
        Schema::drop('acl_permissions');
    }
}
