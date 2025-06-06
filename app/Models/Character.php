<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\MapGenerator;

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

    protected static function booted()
    {
        static::created(function (Character $c) {
            // Génère et persiste la map dès la création du personnage
            $cells = MapGenerator::generate();
            $c->map()->create(['cells' => $cells]);
        });
    }
}
