<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Admin\AdminVehicleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes - AutoRental Application
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
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

    // Profiel
    // Profiel
    Route::get('/profile', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $totalRentals = $user->rentals()->count();
        $activeRentals = $user->rentals()->where('end_date', '>=', now())->count();
        $totalSpent = $user->rentals()->sum('total_price');

        return view('user-profile', compact('totalRentals', 'activeRentals', 'totalSpent'));
    })->name('user.profile');

    Route::put('/profile', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);
        $user->save();

        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'Huidig wachtwoord is onjuist']);
            }
            $user->password = $validated['new_password'];
            $user->save();
        }

        return back()->with('success', 'Profiel bijgewerkt!');
    })->name('user.profile.update');

    // Geschiedenis
    Route::get('/history', [RentalController::class, 'history'])->name('user.history');

    // Reservering annuleren
    Route::delete('/rentals/{id}', [RentalController::class, 'cancel'])->name('rentals.cancel');

    // Account verwijderen
    Route::delete('/profile', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Wachtwoord is verplicht om je account te verwijderen',
            'password.current_password' => 'Wachtwoord is onjuist',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return redirect()->route('home')->with('success', 'Je account is succesvol verwijderd. We hopen je ooit weer terug te zien! 👋');
    })->name('user.profile.delete');
});

// Reservering maken (buiten auth voor redirect)
Route::post('/vehicles/{id}/rent', [RentalController::class, 'store'])->name('vehicles.rent');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $totalVehicles = \App\Models\Vehicle::count();
        $activeRentals = \App\Models\Rental::where('end_date', '>=', now())->count();
        $totalUsers = \App\Models\User::where('role', 'user')->count();
        $totalRevenue = \App\Models\Rental::sum('total_price');
        $vehicles = \App\Models\Vehicle::latest()->get();

        $recentActivities = \App\Models\Rental::with(['user', 'vehicle'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($rental) {
                return [
                    'icon' => '🚗',
                    'title' => $rental->user->name . ' huurde ' . $rental->vehicle->title,
                    'description' => 'Van ' . \Carbon\Carbon::parse($rental->start_date)->format('d-m-Y') .
                        ' tot ' . \Carbon\Carbon::parse($rental->end_date)->format('d-m-Y'),
                    'time' => $rental->created_at->diffForHumans()
                ];
            });

        return view('admin-dashboard', compact(
            'totalVehicles',
            'activeRentals',
            'totalUsers',
            'totalRevenue',
            'vehicles',
            'recentActivities'
        ));
    })->name('dashboard');

    // Voertuig CRUD
    Route::get('/vehicles/create', [AdminVehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [AdminVehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{id}/edit', [AdminVehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{id}', [AdminVehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{id}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');
});

/*
|--------------------------------------------------------------------------
| Fallback Route (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
