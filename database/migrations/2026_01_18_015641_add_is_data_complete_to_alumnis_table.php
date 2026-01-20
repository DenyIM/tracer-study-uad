<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->boolean('is_data_complete')->default(false)->after('npwp');
        });
    }

    public function down()
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->dropColumn('is_data_complete');
        });
    }
};