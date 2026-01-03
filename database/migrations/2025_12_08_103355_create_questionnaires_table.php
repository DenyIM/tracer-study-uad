<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); 
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->boolean('is_general')->default(false); 
            $table->integer('time_estimate')->nullable(); 
            $table->timestamps();
            
            $table->unique(['category_id', 'slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('questionnaires');
    }
};