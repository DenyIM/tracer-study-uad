<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            // Kolom untuk menyimpan nama dari email
            $table->string('name_from_email')->nullable()->after('fullname');
            
            // Pastikan semua kolom opsional bisa null
            $table->string('study_program')->nullable()->change();
            $table->date('graduation_date')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('npwp')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->dropColumn('name_from_email');
        });
    }
};