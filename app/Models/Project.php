<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = "tb_project";
    protected $primarykey = 'id';
    protected $fillable = [
        'name',
    ];
}
