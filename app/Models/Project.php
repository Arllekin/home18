<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function label()
    {
        return $this->belongsToMany(Label::class);
    }

    protected $fillable = [
        'project_name',
        'creator_id',
        'created_at',
        'updated_at,'
    ];
}
