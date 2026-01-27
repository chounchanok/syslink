<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sites (อยู่ภายใต้ Project ตามที่พี่ระบุ) - SOW 1.2.1
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id'); // Link กับตาราง projects
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 8)->nullable(); // พิกัดละติจูด
            $table->decimal('lng', 11, 8)->nullable(); // พิกัดลองจิจูด
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        // 2. Job Templates (อยู่ภายใต้ Job ตามที่พี่ระบุ) - SOW 1.2.2
        Schema::create('job_templates', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // ชื่อ Template งาน เช่น "ติดตั้ง CCTV มาตรฐาน"
            $table->text('description')->nullable();
            $table->integer('estimated_hours')->default(0); // ระยะเวลาประเมิน
            $table->json('checklist')->nullable(); // เก็บรายการ Checklist ย่อยเป็น JSON
            $table->timestamps();
        });

        // 3. Task Logs (Check-in/Out & GPS) - SOW 1.2.4
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id'); // Link กับงานที่ทำ
            $table->unsignedBigInteger('user_id'); // คนเช็คอิน
            $table->dateTime('check_in_time')->nullable();
            $table->decimal('check_in_lat', 10, 8)->nullable();
            $table->decimal('check_in_lng', 11, 8)->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->decimal('check_out_lat', 10, 8)->nullable();
            $table->decimal('check_out_lng', 11, 8)->nullable();
            $table->text('note')->nullable(); // หมายเหตุการปฏิบัติงาน
            $table->timestamps();
        });

        // 4. Task Approvals (ระบบอนุมัติ) - SOW 1.2.6
        Schema::create('task_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('approver_id'); // ผู้จัดการที่อนุมัติ
            $table->enum('status', ['pending', 'approved', 'rejected', 'correction_needed']);
            $table->text('comment')->nullable(); // เหตุผลการอนุมัติ/ปฏิเสธ
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        // 5. Documents (รูปภาพและเอกสาร) - SOW 1.2.5
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            // Polymorphic Relation เพื่อให้แนบไฟล์ได้ทั้งกับ Project, Site, หรือ Task
            $table->unsignedBigInteger('documentable_id');
            $table->string('documentable_type'); // เช่น 'App\Models\Project', 'App\Models\Task'
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable(); // jpg, pdf, docx
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamps();
        });

        // 6. Project Assets (Hardware/Software Tracking) - SOW 1.2.7
        // เป็น Pivot Table ระหว่าง Project กับ Product (Inventory)
        Schema::create('project_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('product_id'); // ดึงจาก Master Product เดิม
            $table->integer('quantity');
            $table->enum('status', ['pending_install', 'installed', 'configured', 'defective'])->default('pending_install');
            $table->dateTime('installed_at')->nullable();
            $table->timestamps();
        });

        // 7. Chats (ระบบสื่อสาร) - SOW 1.2.10
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // ถ้า null คือคุยเดี่ยว
            $table->enum('type', ['private', 'group', 'project_room'])->default('private');
            $table->unsignedBigInteger('related_id')->nullable(); // เช่น project_id
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id');
            $table->unsignedBigInteger('user_id'); // ผู้ส่ง
            $table->text('message')->nullable();
            $table->string('attachment_path')->nullable(); // ส่งรูป/ไฟล์
            $table->timestamps();
        });

        // ตารางจับคู่ user กับห้องแชท
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_participants');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_rooms');
        Schema::dropIfExists('project_assets');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('task_approvals');
        Schema::dropIfExists('task_logs');
        Schema::dropIfExists('job_templates');
        Schema::dropIfExists('sites');
    }
};
