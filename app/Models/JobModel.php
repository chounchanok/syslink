<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobModel extends Model
{
    use HasFactory;
    protected $table = 'tb_job';
    protected $primaryKey = 'id';
    protected $fillable = [
        'job_name',
        'phase',
        'job_date_time',
        'explore_or_install',
        'customer_name',
        'tell',
        'technician',
        'type',
        'google_latitude',
        'google_longitude',
    ];
}
