<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project_submit extends Model
{
    use HasFactory;
    protected $table = "tb_submit_project";
    protected $primarykey = 'id';
    protected $fillable = [
        'project_id',
        'job_id',
        'name',
    ];
}
