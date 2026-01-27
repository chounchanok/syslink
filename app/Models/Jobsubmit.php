<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobsubmit extends Model
{
    use HasFactory;
    protected $table = 'tb_submit_job';
    protected $primarykey = 'id';
    protected $fillable = [
        'job_id',
        'file_name',
        'path',
        'step_file',
        'detail',
        'status'
    ];
}
