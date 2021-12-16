<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use HasFactory, SoftDeletes;

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsToMany(Project::class);
    }

    protected $fillable = [
        'label_body',
        'author_id',
    ];
}
