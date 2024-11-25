<?php

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
            $table->string('name', 255);
            $table->string('last_name', 255)->nullable();
            $table->string('document', 9)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('email', 100)->unique();
            $table->boolean('active')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100);
            $table->string('image', 50)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
