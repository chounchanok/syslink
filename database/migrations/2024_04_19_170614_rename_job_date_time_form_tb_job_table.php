<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameJobDateTimeFormTbJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_job', function (Blueprint $table) {
            $table->renameColumn('job_date_time', 'install_date');
            $table->text('survay_date')->nullable()->after('job_date_time');

        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_job', function (Blueprint $table) {
            //
            $table->renameColumn('install_date', 'job_date_time');

        });
    }
}
