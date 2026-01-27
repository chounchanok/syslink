<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_job', function (Blueprint $table) {
            $table->id();
            $table->text('job_name')->nullable();
            $table->text('job_date_time')->nullable();
            $table->text('explore_or_install')->nullable();
            $table->text('customer_name')->nullable();
            $table->text('tell')->nullable();
            $table->text('type')->nullable();
            $table->text('phase')->nullable();
            $table->text('google_latitude')->nullable();
            $table->text('google_longitude')->nullable();
            $table->text('technician')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_job');
    }
}
