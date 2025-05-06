<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'character_id',
        'cells',
    ];

    protected $casts = [
        'cells' => 'array',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
