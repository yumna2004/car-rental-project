<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RentalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes - AutoRental Application
|--------------------------------------------------------------------------
| Deze routes zijn gekoppeld aan jouw blade templates.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Routes (Geen authenticatie vereist)
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Voertuigen overzicht - NU MET ECHTE DATA!
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');

// Voertuig detail pagina - NU MET ECHTE DATA!
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Gebruikersprofiel (pagina)
    Route::get('/profile', function () {
        // Dummy stats voor nu (later vervangen met echte rentals data)
        $totalRentals = 0;
        $activeRentals = 0;
        $totalSpent = 0;

        return view('user-profile', compact('totalRentals', 'activeRentals', 'totalSpent'));
    })->name('user.profile');

    // ✅ PROFIEL UPDATEN (Echt opslaan in DB)
    Route::put('/profile', function (Request $request) {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],

            // optioneel wachtwoord wijzigen
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Basisgegevens invullen + save (geen update() => minder Intelephense errors)
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);
        $user->save();

        // Wachtwoord wijzigen (alleen als ingevuld)
        if (!empty($validated['new_password'])) {

            if (!Hash::check($validated['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'Huidig wachtwoord is onjuist']);
            }

            // In jouw User model staat meestal: 'password' => 'hashed'
            // Dus je mag de plain string zetten:
            $user->password = $validated['new_password'];
            $user->save();
        }

        return back()->with('success', 'Profiel bijgewerkt!');
    })->name('user.profile.update');

    // Huurgeschiedenis
    Route::get('/history', [RentalController::class, 'history'])
        ->name('user.history');

    // Reservering maken
    Route::post('/vehicles/{id}/rent', [RentalController::class, 'store'])
        ->name('vehicles.rent');

    // Reservering annuleren
    Route::delete('/rentals/{id}', [RentalController::class, 'cancel'])
        ->name('rentals.cancel');

    // ✅ ACCOUNT VERWIJDEREN
    Route::delete('/profile', function (Request $request) {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Extra beveiliging: check wachtwoord
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Wachtwoord is verplicht om je account te verwijderen',
            'password.current_password' => 'Wachtwoord is onjuist',
        ]);

        // Log de gebruiker uit
        Auth::logout();

        // Verwijder alle sessies
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Verwijder de gebruiker (cascade delete verwijdert ook rentals)
        $user->delete();

        // Redirect naar home met melding
        return redirect()->route('home')->with('success', 'Je account is succesvol verwijderd. We hopen je ooit weer terug te zien! 👋');
    })->name('user.profile.delete');
});
// Reservering maken (buiten auth middleware)
Route::post('/vehicles/{id}/rent', [RentalController::class, 'store'])
    ->name('vehicles.rent');
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Let op: admin middleware werkt pas als jij AdminMiddleware + alias hebt gemaakt.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard (demo)
    Route::get('/dashboard', function () {
        $totalVehicles = 0;
        $activeRentals = 0;
        $totalUsers = 0;
        $totalRevenue = 0;
        $vehicles = collect([]);

        $recentActivities = [
            [
                'icon' => '🚗',
                'title' => 'Demo Activiteit',
                'description' => 'Vervang met echte data uit database',
                'time' => 'Zojuist'
            ],
        ];

        return view('admin-dashboard', compact(
            'totalVehicles',
            'activeRentals',
            'totalUsers',
            'totalRevenue',
            'vehicles',
            'recentActivities'
        ));
    })->name('dashboard');

    // Voertuig toevoegen - formulier
    Route::get('/vehicles/create', function () {
        return view('admin-vehicle-form');
    })->name('vehicles.create');

    // Voertuig toevoegen - verwerken (demo)
    Route::post('/vehicles', function () {
        return redirect()->route('admin.dashboard')->with('success', 'Voertuig toegevoegd! (Demo mode)');
    })->name('vehicles.store');

    // Voertuig bewerken - formulier (demo)
    Route::get('/vehicles/{id}/edit', function ($id) {
        $vehicle = (object)[
            'id' => $id,
            'title' => 'Demo Voertuig',
            'description' => 'Dit is een demo voertuig',
            'category' => 'personenauto',
            'price_per_day' => 50.00,
            'region' => 'Amsterdam',
            'transmission' => 'automaat',
            'image' => null,
            'availability_start' => date('Y-m-d'),
            'availability_end' => null,
        ];

        return view('admin-vehicle-form', compact('vehicle'));
    })->name('vehicles.edit');

    // Voertuig updaten (demo)
    Route::put('/vehicles/{id}', function ($id) {
        return redirect()->route('admin.dashboard')->with('success', 'Voertuig bijgewerkt! (Demo mode)');
    })->name('vehicles.update');

    // Voertuig verwijderen (demo)
    Route::delete('/vehicles/{id}', function ($id) {
        return redirect()->route('admin.dashboard')->with('success', 'Voertuig verwijderd! (Demo mode)');
    })->name('vehicles.destroy');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
