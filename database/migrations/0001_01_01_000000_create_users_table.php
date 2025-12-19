<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('users', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('email', 255)->unique();
        //     $table->dateTime('email_verified_at')->nullable();
        //     $table->string('password', 255);
        //     $table->enum('role', ['admin', 'alumni']);
        //     $table->string('provider', 100)->nullable();
        //     $table->string('provider_id', 100)->nullable();
        //     $table->string('verification_string', 100)->nullable();
        //     $table->string('pp_url', 255)->nullable();
        //     $table->dateTime('last_login_at')->nullable();
        //     $table->string('otp_code', 10)->nullable();
        //     $table->dateTime('otp_expires_at')->nullable();
        //     $table->timestamps();
        // });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['alumni', 'admin', 'super_admin'])->default('alumni');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
