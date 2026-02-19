<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVehicleController extends Controller
{
    public function create()
    {
        return view('admin-vehicle-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required|in:personenauto,bestelbus,verhuisbus,aanhangwagen',
            'price_per_day' => 'required|numeric|min:0',
            'region' => 'required|max:255',
            'transmission' => 'required|in:automaat,schakel,n.v.t.',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after_or_equal:availability_start',
        ], [
            'title.required' => 'Titel is verplicht',
            'description.required' => 'Beschrijving is verplicht',
            'category.required' => 'Categorie is verplicht',
            'price_per_day.required' => 'Prijs per dag is verplicht',
            'region.required' => 'Regio is verplicht',
            'transmission.required' => 'Transmissie is verplicht',
            'image.image' => 'Hoofdfoto moet een afbeelding zijn',
            'images.*.image' => 'Alle extra foto\'s moeten afbeeldingen zijn',
        ]);

        // Hoofdfoto
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vehicles', 'public');
        }

        // Meerdere foto's
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('vehicles', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        Vehicle::create($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', '✅ Voertuig succesvol toegevoegd!');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('admin-vehicle-form', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'category' => 'required|in:personenauto,bestelbus,verhuisbus,aanhangwagen',
            'price_per_day' => 'required|numeric|min:0',
            'region' => 'required|max:255',
            'transmission' => 'required|in:automaat,schakel,n.v.t.',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'availability_start' => 'nullable|date',
            'availability_end' => 'nullable|date|after_or_equal:availability_start',
        ]);

        // Hoofdfoto
        if ($request->hasFile('image')) {
            if ($vehicle->image && Storage::disk('public')->exists($vehicle->image)) {
                Storage::disk('public')->delete($vehicle->image);
            }
            $validated['image'] = $request->file('image')->store('vehicles', 'public');
        }

        // Meerdere foto's
        if ($request->hasFile('images')) {
            // Verwijder oude extra foto's
            if ($vehicle->images) {
                foreach ($vehicle->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('vehicles', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        $vehicle->update($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', '✅ Voertuig succesvol bijgewerkt!');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $hasActiveRentals = $vehicle->rentals()
            ->where('end_date', '>=', now())
            ->exists();

        if ($hasActiveRentals) {
            return back()->with('error', '❌ Kan voertuig niet verwijderen: er zijn nog actieve reserveringen!');
        }

        // Verwijder alle foto's
        if ($vehicle->image && Storage::disk('public')->exists($vehicle->image)) {
            Storage::disk('public')->delete($vehicle->image);
        }

        if ($vehicle->images) {
            foreach ($vehicle->images as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }

        $vehicle->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', '✅ Voertuig succesvol verwijderd!');
    }
}
