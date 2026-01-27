<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTbProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_project', function (Blueprint $table) {
            // เพิ่มคอลัมน์ใหม่โดยไม่กระทบข้อมูลเดิม
            if (!Schema::hasColumn('tb_project', 'code')) {
                $table->string('code')->nullable()->after('name'); // รหัสโครงการ
            }
            if (!Schema::hasColumn('tb_project', 'status')) {
                $table->enum('status', ['planned', 'active', 'completed', 'hold'])->default('active')->after('code'); // สถานะ
            }
            if (!Schema::hasColumn('tb_project', 'start_date')) {
                $table->date('start_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('tb_project', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
        });
    }

    public function down()
    {
        Schema::table('tb_project', function (Blueprint $table) {
            $table->dropColumn(['code', 'status', 'start_date', 'end_date']);
        });
    }
}
