<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDetailFormTbSubmitJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_submit_job', function (Blueprint $table) {
            $table->text('detail')->nullable()->after('file_name')->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_submit_job', function (Blueprint $table) {
            $table->text('detail')->change();

        });
    }
}
