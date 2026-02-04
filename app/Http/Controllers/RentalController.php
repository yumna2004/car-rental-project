<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Reservering opslaan
     * 
     * Route: POST /vehicles/{id}/rent
     */
    public function store(Request $request, $id)
    {
        // Check of user ingelogd is
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Je moet ingelogd zijn om te reserveren.');
        }

        // Haal voertuig op
        $vehicle = Vehicle::findOrFail($id);

        // 7.2 DATUM VALIDATIE
        $validated = $request->validate([
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today', // Niet in verleden
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date', // Na of op startdatum
            ],
        ], [
            'start_date.required' => 'Startdatum is verplicht',
            'start_date.after_or_equal' => 'Startdatum kan niet in het verleden zijn',
            'end_date.required' => 'Einddatum is verplicht',
            'end_date.after_or_equal' => 'Einddatum moet na of op de startdatum zijn',
        ]);
        // 8.1 US19 - CHECK OF VOERTUIG AL GERESERVEERD IS
        $hasOverlap = Rental::where('vehicle_id', $vehicle->id)
            ->where(function ($query) use ($validated) {
                // Check alle mogelijke overlap scenario's
                $query->where(function ($q) use ($validated) {
                    // Scenario 1: Nieuwe start valt binnen bestaande reservering
                    $q->where('start_date', '<=', $validated['start_date'])
                        ->where('end_date', '>=', $validated['start_date']);
                })
                    ->orWhere(function ($q) use ($validated) {
                        // Scenario 2: Nieuwe eind valt binnen bestaande reservering
                        $q->where('start_date', '<=', $validated['end_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    })
                    ->orWhere(function ($q) use ($validated) {
                        // Scenario 3: Nieuwe reservering omvat hele bestaande
                        $q->where('start_date', '>=', $validated['start_date'])
                            ->where('end_date', '<=', $validated['end_date']);
                    });
            })
            ->exists();

        // Als er overlap is, stop en geef error
        if ($hasOverlap) {
            return back()
                ->withInput()
                ->with('error', '⚠️ Dit voertuig is al gereserveerd in de gekozen periode. Kies andere datums.');
        }
        // 7.3 PRIJS BEREKENEN
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        // Bereken aantal dagen (inclusief start en eind)
        $days = $startDate->diffInDays($endDate) + 1;

        // Bereken totaalprijs
        $totalPrice = $days * $vehicle->price_per_day;

        // 7.4 RESERVERING OPSLAAN
        $rental = Rental::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_price' => $totalPrice,
        ]);

        // 7.5 BEVESTIGING TONEN
        return redirect()
            ->route('vehicles.show', $vehicle->id)
            ->with('success', "✅ Reservering gelukt! Je hebt {$vehicle->title} gereserveerd van {$startDate->format('d-m-Y')} tot {$endDate->format('d-m-Y')} voor €" . number_format($totalPrice, 2) . " ({$days} dagen).");
    }

    /**
     * Toon verhuurgeschiedenis van ingelogde user
     * 
     * Route: GET /history
     */
    public function history()
    {
    // Haal alle rentals van de ingelogde user met voertuig info
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rentals = $user->rentals()
            ->with('vehicle')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user-history', compact('rentals'));
    }

    /**
     * Annuleer een reservering
     * 
     * Route: DELETE /rentals/{id}
     */
    public function cancel($id)
    {
        $rental = Rental::findOrFail($id);

        // Check of reservering van ingelogde user is
        if ($rental->user_id !== Auth::id()) {
            return back()->with('error', 'Je kunt alleen je eigen reserveringen annuleren.');
        }

        // Check of reservering nog niet begonnen is
        if (Carbon::parse($rental->start_date)->isPast()) {
            return back()->with('error', 'Je kunt geen actieve of afgelopen reserveringen annuleren.');
        }

        $rental->delete();

        return back()->with('success', 'Reservering succesvol geannuleerd! 🗑️');
    }
}
