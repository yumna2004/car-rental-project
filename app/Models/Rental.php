<?php

namespace App\Models;

// Imports
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Rental extends Model
{
    /**
     * Dit zijn de velden die je kunt invullen bij Rental::create()
     */
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

    /**
     * Is deze rental nu actief?
     * (tussen start_date en end_date)
     * @return bool
     */
    public function isActive(): bool
    {
        return now()->between($this->start_date, $this->end_date);
    }

    /**
     * Begint deze rental in de toekomst?
     * @return bool
     */
    public function isUpcoming(): bool
    {
        return now()->lt($this->start_date);
    }

    /**
     * Is deze rental al afgelopen?
     * @return bool
     */
    public function isCompleted(): bool
    {
        return now()->gt($this->end_date);
    }

    /**
     * ACCESSORS (Automatische Attributen)
     */

    /**
     * Krijg de status als tekst
     */
    public function getStatusAttribute(): string
    {
        if ($this->isActive()) {
            return 'active';      // nu bezig
        } elseif ($this->isUpcoming()) {
            return 'upcoming';    // n de toekomst
        } else {
            return 'completed';   //al voorbij
        }
    }

    /**
     * Bereken aantal dagen
     */
    public function getDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Krijg een mooie status tekst in het Nederlands
     * Returns: "Actief", "Binnenkort", of "Voltooid"
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Actief',
            'upcoming' => 'Binnenkort',
            'completed' => 'Voltooid',
            default => 'Onbekend',
        };
    }

    /**
     * Krijg een mooie kleur voor de status (voor badges)
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'green',      // Groen voor actief
            'upcoming' => 'blue',     // Blauw voor binnenkort
            'completed' => 'gray',    // Grijs voor voltooid
            default => 'gray',
        };
    }
}
