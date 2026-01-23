<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fullname', 255);
            $table->string('nim', 20)->unique();
            $table->date('date_of_birth')->nullable(); 
            $table->string('phone', 20)->nullable();
            $table->string('study_program', 100);
            $table->date('graduation_date');
            $table->string('npwp', 50)->nullable();
            $table->integer('points')->default(0); 
            $table->boolean('is_data_complete')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnis');
    }
};