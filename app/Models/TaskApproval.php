<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'approver_id',
        'status', // pending, approved, rejected
        'comment',
        'approved_at'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function approver()
    {
        // เชื่อมกับ User ที่เป็นคนอนุมัติ (Manager)
        return $this->belongsTo(User::class, 'approver_id');
    }
}
