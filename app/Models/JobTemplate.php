<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'estimated_hours',
        'checklist' // เก็บเป็น JSON
    ];

    // แปลง JSON ใน Database เป็น Array อัตโนมัติเวลาดึงไปใช้
    protected $casts = [
        'checklist' => 'array',
    ];
}
