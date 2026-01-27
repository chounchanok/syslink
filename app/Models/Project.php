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

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function assets()
    {
        return $this->hasMany(ProjectAsset::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
