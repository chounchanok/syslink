<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSaleToTbJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_job', function (Blueprint $table) {
            $table->text('sale')->nullable();
            $table->text('address_customer')->nullable();
            $table->text('inspect')->nullable();
            $table->renameColumn('explore_or_install', 'status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_job', function (Blueprint $table) {
            //
            $table->renameColumn('status', 'explore_or_install');

        });
    }
}
