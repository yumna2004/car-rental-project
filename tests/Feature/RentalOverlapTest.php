<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Rental;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RentalOverlapTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_can_be_reserved_if_no_overlap(): void
    {
        // ARRANGE
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $vehicle = Vehicle::factory()->create([
            'price_per_day' => 50,
        ]);

        $this->actingAs($user);

        $start = now()->addDays(2)->toDateString();
        $end   = now()->addDays(5)->toDateString();

        // ACT
        $response = $this->post("/vehicles/{$vehicle->id}/rent", [
            'start_date' => $start,
            'end_date'   => $end,
        ]);

        // ASSERT
        $response->assertSessionHasNoErrors();

        // Omdat mijn DB start_date/end_date als datetime opslaat (00:00:00),
        // checken ik op datum (zonder tijd) via whereDate.
        $this->assertTrue(
            Rental::query()
                ->where('vehicle_id', $vehicle->id)
                ->where('user_id', $user->id)
                ->whereDate('start_date', $start)
                ->whereDate('end_date', $end)
                ->exists(),
            'Rental record is niet gevonden met de verwachte datums (check whereDate).'
        );
    }

    public function test_vehicle_cannot_be_reserved_if_dates_overlap(): void
    {
        // ARRANGE
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $vehicle = Vehicle::factory()->create([
            'price_per_day' => 50,
        ]);

        // Bestaande reservering (in de toekomst)
        $existingStart = now()->addDays(2)->toDateString();
        $existingEnd   = now()->addDays(5)->toDateString();

        Rental::create([
            'user_id'     => $user->id,
            'vehicle_id'  => $vehicle->id,
            'start_date'  => $existingStart,
            'end_date'    => $existingEnd,
            'total_price' => 250,
        ]);

        $this->actingAs($user);

        // Nieuwe aanvraag die overlapt
        $newStart = now()->addDays(4)->toDateString(); // overlapt
        $newEnd   = now()->addDays(7)->toDateString();

        // Aantal rentals vóór de actie
        $countBefore = Rental::count();

        // ACT
        $response = $this->post("/vehicles/{$vehicle->id}/rent", [
            'start_date' => $newStart,
            'end_date'   => $newEnd,
        ]);

        // Er mag GEEN extra rental worden aangemaakt bij overlap.
        $countAfter = Rental::count();
        $this->assertSame(
            $countBefore,
            $countAfter,
            'Er is toch een extra rental aangemaakt terwijl de periode overlapt.'
        );

        // Optioneel: als mijn app wél errors terugstuurt, is dit extra bewijs.
        // (Dus ik checken het conditioneel.)
        if (session()->has('errors')) {
            $response->assertSessionHasErrors();
        }
    }
}
