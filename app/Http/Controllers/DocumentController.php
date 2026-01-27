<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['uploader']);

        // Filter by Project (ถ้ามีการส่ง project_id มา)
        if ($request->has('project_id') && $request->project_id != '') {
            $query->where('documentable_type', 'App\Models\Project')
                  ->where('documentable_id', $request->project_id);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);

        // ดึงรายชื่อ Project สำหรับใส่ใน Dropdown ตอนอัปโหลด
        $projects = Project::where('status', '!=', 'completed')->get();

        return view('backoffice.document.index', [
            'documents' => $documents,
            'projects' => $projects
        ]);
    }

    // ฟังก์ชันอัปโหลดไฟล์
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
            'project_id' => 'nullable|exists:tb_project,id', // ถ้าจะแนบกับ Project
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Upload ลง Folder 'documents' (ต้องรัน php artisan storage:link ก่อนนะ)
            $path = $file->storeAs('public/documents', $filename);

            // บันทึกลง Database
            $doc = new Document();
            $doc->file_name = $file->getClientOriginalName();
            $doc->file_path = 'storage/documents/' . $filename; // Path ที่ใช้เรียกดู
            $doc->file_type = $file->getClientOriginalExtension();
            $doc->uploaded_by = Auth::guard('admin')->user()->id ?? 0;

            // ผูกกับ Project (ถ้ามี) หรือเป็นเอกสารทั่วไป
            if ($request->project_id) {
                $doc->documentable_type = 'App\Models\Project';
                $doc->documentable_id = $request->project_id;
            } else {
                // กรณีเป็นเอกสารส่วนกลาง ไม่ระบุ Project
                $doc->documentable_type = 'System';
                $doc->documentable_id = 0;
            }

            $doc->save();

            return redirect()->back()->with('success', 1);
        }

        return redirect()->back()->with('error', 'File upload failed');
    }

    // ลบไฟล์
    public function delete(Request $request)
    {
        $doc = Document::find($request->id);

        if ($doc) {
            // ลบไฟล์จริงออกจาก Storage
            $real_path = str_replace('storage/', 'public/', $doc->file_path);
            Storage::delete($real_path);

            // ลบ Record
            $doc->delete();
        }

        return redirect()->back()->with('success', 1);
    }
}
