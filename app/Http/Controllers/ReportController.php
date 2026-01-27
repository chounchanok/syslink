<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. รับค่า Filter (ถ้าไม่ส่งมา ให้ Default เป็นเดือนปัจจุบัน)
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $project_id = $request->project_id;

        // 2. Query ข้อมูลพื้นฐาน
        $taskQuery = Task::whereDate('start', '>=', $start_date)
                         ->whereDate('start', '<=', $end_date);

        $logQuery = TaskLog::whereDate('created_at', '>=', $start_date)
                           ->whereDate('created_at', '<=', $end_date);

        if ($project_id) {
            // ถ้าเลือกโปรเจกต์ ให้กรองเฉพาะงานของโปรเจกต์นั้น
            // (สมมติว่า Task มี project_id, ถ้าไม่มีอาจต้อง join ผ่าน site)
            $taskQuery->where('project_id', $project_id);

            // Log ต้อง Join กับ Task เพื่อหา project_id
            $logQuery->whereHas('task', function($q) use ($project_id) {
                $q->where('project_id', $project_id);
            });
        }

        // 3. ดึงข้อมูลสรุป (Dashboard KPIs)
        $total_tasks = (clone $taskQuery)->count();
        $completed_tasks = (clone $taskQuery)->where('status', 'completed')->count();
        $pending_tasks = (clone $taskQuery)->where('status', '!=', 'completed')->count();

        // คำนวณ % ความสำเร็จ
        $progress_percent = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100, 2) : 0;

        // 4. ดึงข้อมูลตารางรายงาน (Task Details)
        $tasks = $taskQuery->with(['project', 'site'])->orderBy('start', 'asc')->get();

        // เตรียมข้อมูล Dropdown
        $projects = Project::all();

        return view('backoffice.report.index', [
            'projects' => $projects,
            'tasks' => $tasks,
            'summary' => [
                'total' => $total_tasks,
                'completed' => $completed_tasks,
                'pending' => $pending_tasks,
                'progress' => $progress_percent
            ],
            'filters' => [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'project_id' => $project_id
            ]
        ]);
    }
}
