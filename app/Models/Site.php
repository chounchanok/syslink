<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'address',
        'lat',
        'lng',
        'contact_person',
        'contact_phone'
    ];

    // ความสัมพันธ์: ไซต์งานนี้เป็นของโครงการไหน
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // ความสัมพันธ์: ไซต์งานนี้มีงาน (Tasks) อะไรบ้าง
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
