<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; // อย่าลืมสร้าง Model Task นะครับ
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        // ส่งข้อมูล Projects ไปทำ Filter ในอนาคต
        return view('backoffice.calendar.index');
    }

    public function getEvents(Request $request)
    {
        // ดึง Task มาแสดงตาม Range วันที่ที่ Calendar ขอมา
        $start = $request->query('start');
        $end = $request->query('end');

        $tasks = Task::whereDate('start', '>=', $start)
                     ->whereDate('start', '<=', $end)
                     ->get();

        $events = $tasks->map(function ($task) {
            $color = match ($task->status) {
                'completed' => '#10B981', // green-500
                'in_progress' => '#3B82F6', // blue-500
                'waiting_approval' => '#F59E0B', // amber-500
                'rejected' => '#EF4444', // red-500
                default => '#6B7280', // gray-500
            };

            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->start,
                'end' => $task->end,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => $task->status,
                    'site' => $task->site_id ? 'Site A' : 'Office' // ตัวอย่าง
                ]
            ];
        });

        return response()->json($events);
    }

    public function updateDragDrop(Request $request)
    {
        // Logic บันทึกเมื่อมีการ Drag & Drop
        $task = Task::find($request->id);
        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $task->update([
            'start' => Carbon::parse($request->start)->toDateTimeString(),
            'end' => $request->end ? Carbon::parse($request->end)->toDateTimeString() : null,
        ]);

        return response()->json(['success' => true]);
    }
}
