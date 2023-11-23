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
            $table->renameColumn('data_lahan', 'informasi_lahan');
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
            $table->renameColumn('informasi_lahan', 'data_lahan');
        });
    }
};
