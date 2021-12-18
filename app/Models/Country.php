<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    public function continent()
    {
       return $this->belongsTo(Continent::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    protected $fillable = [
        'country_kode',
        'country_name',
        'continent_id',
        'created_at',
        'updated_at'
    ];
}
