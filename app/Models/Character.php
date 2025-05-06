<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'race',
        'class',
        'level',
        'xp',
        'hp',
        'max_hp',
        'armor',
        'power',
        'x',
        'y',
        'equipped_items',
    ];

    protected $casts = [
        'equipped_items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function map()
    {
        return $this->hasOne(Map::class);
    }
}
