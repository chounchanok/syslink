<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFile extends Model
{
    use HasFactory;
    protected $table = "tb_file_job";
    protected $primarykey = 'id';
    protected $fillable = [
        'job_id',
        'file_name',
        'path',
        'type'
    ];
}
