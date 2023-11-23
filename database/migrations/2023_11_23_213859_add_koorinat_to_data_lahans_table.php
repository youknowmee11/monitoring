<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_lahans', function (Blueprint $table) {
            $table->string('longitude')->after('informasi_lahan')->default(0);
            $table->string('latitude')->after('longitude')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_lahans', function (Blueprint $table) {
            //
        });
    }
};
