<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobsignature extends Model
{
    use HasFactory;
    protected $table = 'tb_submit_signature';
    protected $fillable = [
        'job_id',
        'path',
        'signature',
    ];

}
