<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'description',
        'is_published',
        'image_url',
    ];
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
