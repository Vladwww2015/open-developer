<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('developer.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable(config('developer.database.users_table'))) {

            Schema::create(config('developer.database.users_table'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('username', 190)->unique();
                $table->string('password', 60);
                $table->string('name');
                $table->string('avatar')->nullable();
                $table->string('remember_token', 100)->nullable();
                $table->timestamps();
            });
        }
        if(!Schema::hasTable(config('developer.database.roles_table'))) {
            Schema::create(config('developer.database.roles_table'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50)->unique();
                $table->string('slug', 50)->unique();
                $table->timestamps();
            });
        }
        if(!Schema::hasTable(config('developer.database.permissions_table'))) {
            Schema::create(config('developer.database.permissions_table'), function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50)->unique();
                $table->string('slug', 50)->unique();
                $table->string('http_method')->nullable();
                $table->text('http_path')->nullable();
                $table->timestamps();
            });
        }
        if(!Schema::hasTable(config('developer.database.menu_table'))) {
            Schema::create(config('developer.database.menu_table'), function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_id')->default(0);
                $table->integer('order')->default(0);
                $table->string('title', 50);
                $table->string('icon', 50);
                $table->string('uri')->nullable();
                $table->string('permission')->nullable();

                $table->timestamps();
            });
        }

        if(!Schema::hasTable(config('developer.database.role_users_table'))) {
            Schema::create(config('developer.database.role_users_table'), function (Blueprint $table) {
                $table->integer('role_id');
                $table->integer('user_id');
                $table->index(['role_id', 'user_id']);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable(config('developer.database.role_permissions_table'))) {
            Schema::create(config('developer.database.role_permissions_table'), function (Blueprint $table) {
                $table->integer('role_id');
                $table->integer('permission_id');
                $table->index(['role_id', 'permission_id']);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable(config('developer.database.user_permissions_table'))) {
            Schema::create(config('developer.database.user_permissions_table'), function (Blueprint $table) {
                $table->integer('user_id');
                $table->integer('permission_id');
                $table->index(['user_id', 'permission_id']);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable(config('developer.database.role_menu_table'))) {
            Schema::create(config('developer.database.role_menu_table'), function (Blueprint $table) {
                $table->integer('role_id');
                $table->integer('menu_id');
                $table->index(['role_id', 'menu_id']);
                $table->timestamps();
            });
        }
        if(!Schema::hasTable(config('developer.database.operation_log_table'))) {

            Schema::create(config('developer.database.operation_log_table'), function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('path');
                $table->string('method', 10);
                $table->string('ip');
                $table->text('input');
                $table->index('user_id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('developer.database.users_table'));
        Schema::dropIfExists(config('developer.database.roles_table'));
        Schema::dropIfExists(config('developer.database.permissions_table'));
        Schema::dropIfExists(config('developer.database.menu_table'));
        Schema::dropIfExists(config('developer.database.user_permissions_table'));
        Schema::dropIfExists(config('developer.database.role_users_table'));
        Schema::dropIfExists(config('developer.database.role_permissions_table'));
        Schema::dropIfExists(config('developer.database.role_menu_table'));
        Schema::dropIfExists(config('developer.database.operation_log_table'));
    }
}
