<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function project() {
        return $this->belongsTo(Project::class, 'project_id'); // ถ้ามี column project_id
    }

    public function site() {
        return $this->belongsTo(Site::class, 'site_id'); // ถ้ามี column site_id
    }
}
