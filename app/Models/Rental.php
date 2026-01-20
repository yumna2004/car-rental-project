<?php

namespace App\Models;

// Imports
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Rental extends Model
{
    /**
     * $fillable = Welke velden mag je invullen
     * 
     * Dit zijn de velden die je kunt invullen bij Rental::create()
     */
    protected $fillable = [
        'user_id',      // ID van de gebruiker die huurt
        'vehicle_id',   // ID van het voertuig dat gehuurd wordt
        'start_date',   // Startdatum van de huur
        'end_date',     // Einddatum van de huur
        'total_price',  // Totale prijs
    ];

    /**
     * $casts = Verander types automatisch
     * 
     * - Dates worden Carbon objecten (makkelijk rekenen met datums)
     * - total_price wordt een decimal met 2 decimalen
     */
    protected $casts = [
        'start_date' => 'date',     // String → Carbon datum
        'end_date' => 'date',       // String → Carbon datum
        'total_price' => 'decimal:2', // String → Decimal (bijv. 149.99)
    ];

    /**
     * ═══════════════════════════════════════════════════
     * RELATIES (Relationships)
     * ═══════════════════════════════════════════════════
     */

    /**
     * Een Rental hoort bij 1 User
     * 
     * belongsTo = "hoort bij"
     * Veel Rentals → 1 User
     * 
     * Gebruik: $rental->user (geeft de gebruiker die dit gehuurd heeft)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Een Rental hoort bij 1 Vehicle
     * 
     * belongsTo = "hoort bij"
     * Veel Rentals → 1 Vehicle
     * 
     * Gebruik: $rental->vehicle (geeft het voertuig dat gehuurd is)
     */
    // public function vehicle(): BelongsTo
    // {
    //     return $this->belongsTo(Vehicle::class);
    // }

    /**
     * ═══════════════════════════════════════════════════
     * HELPER FUNCTIES - STATUS CHECKS
     * ═══════════════════════════════════════════════════
     */

    /**
     * Is deze rental nu actief?
     * (tussen start_date en end_date)
     * 
     * Gebruik: if ($rental->isActive()) { ... }
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        // now() = vandaag
        // between() = zit tussen start en eind?
        return now()->between($this->start_date, $this->end_date);
    }

    /**
     * Begint deze rental in de toekomst?
     * (start_date is later dan vandaag)
     * 
     * Gebruik: if ($rental->isUpcoming()) { ... }
     * 
     * @return bool
     */
    public function isUpcoming(): bool
    {
        // lt = "less than" = kleiner dan
        return now()->lt($this->start_date);
    }

    /**
     * Is deze rental al afgelopen?
     * (end_date is eerder dan vandaag)
     * 
     * Gebruik: if ($rental->isCompleted()) { ... }
     * 
     * @return bool
     */
    public function isCompleted(): bool
    {
        // gt = "greater than" = groter dan
        return now()->gt($this->end_date);
    }

    /**
     * ═══════════════════════════════════════════════════
     * ACCESSORS (Automatische Attributen)
     * ═══════════════════════════════════════════════════
     */

    /**
     * Krijg de status als tekst
     * 
     * Gebruik: $rental->status
     * Returns: 'active', 'upcoming', of 'completed'
     * 
     * Dit is een "accessor" - je kunt het aanroepen als
     * een gewoon attribuut, maar het wordt berekend!
     */
    public function getStatusAttribute(): string
    {
        if ($this->isActive()) {
            return 'active';      // "Actief" - nu bezig
        } elseif ($this->isUpcoming()) {
            return 'upcoming';    // "Binnenkort" - in de toekomst
        } else {
            return 'completed';   // "Voltooid" - al voorbij
        }
    }

    /**
     * Bereken aantal dagen
     * 
     * Gebruik: $rental->days
     * Returns: 5 (als het 5 dagen is)
     * 
     * Verschil tussen start_date en end_date in dagen
     */
    public function getDaysAttribute(): int
    {
        // diffInDays() = verschil in dagen
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Krijg een mooie status tekst in het Nederlands
     * 
     * Gebruik: $rental->status_label
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
     * 
     * Gebruik: $rental->status_color
     * Returns: 'green', 'blue', of 'gray'
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
