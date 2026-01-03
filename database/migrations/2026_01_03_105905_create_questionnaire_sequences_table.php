<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questionnaire_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('questionnaire_id')->constrained()->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('unlocks_next')->default(true); // Apakah menyelesaikan bagian ini membuka bagian berikutnya
            $table->timestamps();
            
            $table->unique(['category_id', 'questionnaire_id']);
            $table->index(['category_id', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('questionnaire_sequences');
    }
};