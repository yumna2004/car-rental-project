<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    /**
     * Velden die mass-assignable zijn
     * (kunnen ingevuld worden met Vehicle::create())
     */
    protected $fillable = [
        'title',
        'description',
        'category',
        'price_per_day',
        'region',
        'transmission',
        'image',
        'images',
        'availability_start',
        'availability_end',
    ];
    use HasFactory;

    /**
     * Type casting voor specifieke velden
     */
    protected $casts = [
        'price_per_day' => 'decimal:2',        
        'availability_start' => 'date',       
        'availability_end' => 'date',        
        'images' => 'array',
    ];

    /**
     * Relatie: Een Vehicle heeft meerdere Rentals
     * 
     * Gebruik: $vehicle->rentals
     * Geeft: Alle reserveringen van dit voertuig
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Helper: Voortuig beschikbarheid checken
     */
    public function isAvailableForPeriod($startDate, $endDate): bool
    {
        // Check of er overlappende rentals zijn
        $overlappingRentals = $this->rentals()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        return !$overlappingRentals;
    }

    /**
     * Helper: Aantal dagen prijs berekening
     */
    public function getPriceForDays(int $days): float
    {
        return $this->price_per_day * $days;
    }
}
