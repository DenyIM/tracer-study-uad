<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questions_id')->constrained()->onDelete('cascade');
            $table->string('nilai'); // untuk disimpan di DB
            $table->string('label'); // untuk ditampilkan
            $table->integer('urutan');
            $table->boolean('memerlukan_input_lainnya')->default(false); // untuk opsi "Lainnya"
            $table->string('placeholder_input_lainnya')->nullable();
            $table->timestamps();
            
            $table->index(['questions_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
