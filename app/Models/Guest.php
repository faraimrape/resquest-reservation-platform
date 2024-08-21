<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    use HasFactory;

    // Define the relationship: A guest belongs to a reservation
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
