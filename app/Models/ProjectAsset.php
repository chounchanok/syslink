<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'product_id',
        'quantity',
        'status', // pending_install, installed, defective
        'installed_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        // เชื่อมกับ Master Product เดิมของพี่
        return $this->belongsTo(Product::class);
    }
}
