<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Toon alle voertuigen (met paginatie)
     * 
     * Route: GET /vehicles
     * View: vehicles-index.blade.php
     */
    public function index(Request $request)
    {
        // Start met alle voertuigen
        $query = Vehicle::query();

        // 🔍 ZOEKEN - Filter op titel of beschrijving
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 📦 CATEGORIE - Filter op categorie
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // 📍 REGIO - Filter op regio
        if ($request->filled('region')) {
            $query->where('region', $request->input('region'));
        }

        // ⚙️ TRANSMISSIE - Filter op transmissie
        if ($request->filled('transmission')) {
            $query->where('transmission', $request->input('transmission'));
        }

        // 💰 PRIJS - Filter op maximale prijs per dag
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->input('max_price'));
        }

        // Sorteer op nieuwste eerst
        $query->orderBy('created_at', 'desc');

        // Paginatie (12 per pagina) + behoud filters in URL
        $vehicles = $query->paginate(12)->withQueryString();

        return view('vehicles-index', compact('vehicles'));
    }


    /**
     * Toon 1 specifiek voertuig
     * 
     * Route: GET /vehicles/{id}
     * View: vehicle-show.blade.php
     */
    public function show($id)
    {
        // Haal voertuig op, of geef 404 als niet bestaat
        $vehicle = Vehicle::findOrFail($id);

        // US19 demo velden (later implementeren we dit echt)
        $isReserved = false;
        $reservationEndDate = null;

        return view('vehicle-show', compact('vehicle', 'isReserved', 'reservationEndDate'));
    }
}
