<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'reservation_id', // Add this line
    ];

    // Define the relationship: A guest belongs to a reservation
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
