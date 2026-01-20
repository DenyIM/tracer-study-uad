<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');
            $table->enum('category', ['seminar', 'event', 'tips', 'bootcamp', 'other']);
            $table->string('title', 200);
            $table->text('description');
            $table->timestamp('date_time')->nullable();
            $table->string('location', 200)->nullable();
            $table->string('link', 500)->nullable();
            $table->string('contact', 200)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('points_awarded')->default(0);
            $table->text('admin_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
        
        Schema::create('job_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');
            $table->string('company_name', 200);
            $table->string('position', 200);
            $table->string('location', 200);
            $table->text('job_description');
            $table->text('qualifications');
            $table->enum('field', ['it', 'marketing', 'finance', 'hrd', 'engineering', 'other']);
            $table->date('deadline')->nullable();
            $table->string('link', 500);
            $table->string('contact', 200)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('points_awarded')->default(0);
            $table->text('admin_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_submissions');
        Schema::dropIfExists('forum_submissions');
    }
};