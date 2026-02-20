<?php

namespace App\Models;

// Imports
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'total_price',
    ];

    /**
     * - Dates worden Carbon objecten (makkelijk rekenen met datums)
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * RELATIES (Relationships)
     */

    /**
     * Een Rental hoort bij 1 User
     * Veel Rentals → 1 User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Een Rental hoort bij 1 Vehicle
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * HELPER FUNCTIES - STATUS CHECKS
     */

    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }

    public function isUpcoming(): bool
    {
        return now()->lt($this->start_date);
    }

    public function isCompleted(): bool
    {
        return now()->gt($this->end_date);
    }

    /**
     * ACCESSORS (Automatische Attributen)
     */

    /* Status als tekst voorgeven*/
    public function getStatusAttribute(): string
    {
        if ($this->isActive()) {
            return 'active';  
        } elseif ($this->isUpcoming()) {
            return 'upcoming'; 
        } else {
            return 'completed'; 
        }
    }

    /* Bereken aantal dagen */
    public function getDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /* Status */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Actief',
            'upcoming' => 'Binnenkort',
            'completed' => 'Voltooid',
            default => 'Onbekend',
        };
    }

    /* Status kleur */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'green',  
            'upcoming' => 'blue',    
            'completed' => 'gray',    
            default => 'gray',
        };
    }
}
