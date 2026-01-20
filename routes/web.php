<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - AutoRental Application
|--------------------------------------------------------------------------
|
| Deze routes zijn specifiek gemaakt voor de AutoRental blade templates.
| Alle routes zijn gekoppeld aan de juiste views en controllers.
|
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

// Voertuigen overzicht met filters
Route::get('/vehicles', function () {
    // Dummy data voor nu - vervang later met echte controller logica
    // BELANGRIJK: We gebruiken een lege paginator voor de blade template
    $vehicles = new \Illuminate\Pagination\LengthAwarePaginator(
        [], // items (leeg voor nu)
        0,  // total items
        12, // per page
        request()->get('page', 1), // current page
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // TODO: Vervang met echte data:
    // $vehicles = Vehicle::query()
    //     ->when(request('category'), fn($q) => $q->where('category', request('category')))
    //     ->when(request('transmission'), fn($q) => $q->where('transmission', request('transmission')))
    //     ->when(request('region'), fn($q) => $q->where('region', request('region')))
    //     ->when(request('max_price'), fn($q) => $q->where('price_per_day', '<=', request('max_price')))
    //     ->paginate(12);

    return view('vehicles-index', compact('vehicles'));
})->name('vehicles.index');

// Voertuig detail pagina
Route::get('/vehicles/{id}', function ($id) {
    // Dummy data voor nu - vervang later met echte controller logica
    $vehicle = (object)[
        'id' => $id,
        'title' => 'Demo Voertuig',
        'description' => 'Dit is een demo voertuig. Vervang met echte data uit database.',
        'category' => 'personenauto',
        'price_per_day' => 50.00,
        'region' => 'Amsterdam',
        'transmission' => 'automaat',
        'image' => null,
    ];

    // Check of voertuig gereserveerd is (US19)
    $isReserved = false;
    $reservationEndDate = null;

    return view('vehicle-show', compact('vehicle', 'isReserved', 'reservationEndDate'));
})->name('vehicles.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login pagina (GET)
// Route::get('/login', function () {
//     return view('login');
// })->name('login');

// Login verwerken (POST)
// Route::post('/login', function () {
// TODO: Implementeer login logica
// Voorbeeld:
// $credentials = request()->only('email', 'password');
// if (Auth::attempt($credentials)) {
//     return redirect()->intended('/');
// }
// return back()->withErrors(['email' => 'Ongeldige inloggegevens']);

//     return redirect()->route('home')->with('error', 'Login logica nog niet geïmplementeerd');
// });
// Login pagina (GET)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Login verwerken (POST)
Route::post('/login', [AuthController::class, 'login']);
// Registratie pagina (GET)
// Route::get('/register', function () {
//     return view('register');
// })->name('register');

// Registratie verwerken (POST)
// Route::post('/register', function () {
// TODO: Implementeer registratie logica
// Voorbeeld:
// $validated = request()->validate([
//     'name' => 'required|string|max:255',
//     'email' => 'required|email|unique:users',
//     'password' => 'required|min:8|confirmed',
//     'phone' => 'required',
//     'address' => 'required',
// ]);
// 
// $user = User::create([
//     'name' => $validated['name'],
//     'email' => $validated['email'],
//     'password' => Hash::make($validated['password']),
//     'phone' => $validated['phone'],
//     'address' => $validated['address'],
//     'role' => 'user',
// ]);
//
// Auth::login($user);
// return redirect()->route('home')->with('success', 'Account succesvol aangemaakt!');

//     return redirect()->route('login')->with('info', 'Registratie logica nog niet geïmplementeerd');
// });
// Registratie pagina (GET)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Registratie verwerken (POST)
Route::post('/register', [AuthController::class, 'register']);

// Logout (POST)
// Route::post('/logout', function () {
// TODO: Implementeer logout logica
// Auth::logout();
// request()->session()->invalidate();
// request()->session()->regenerateToken();

//     return redirect()->route('home')->with('success', 'Je bent uitgelogd');
// })->name('logout');
// Logout (POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
| Deze routes zijn alleen toegankelijk voor ingelogde gebruikers
*/

Route::middleware(['auth'])->group(function () {

    // Gebruikersprofiel
    Route::get('/profile', function () {
        // Dummy stats voor nu
        $totalRentals = 0;
        $activeRentals = 0;
        $totalSpent = 0;

        // TODO: Vervang met echte data
        // $totalRentals = auth()->user()->rentals()->count();
        // $activeRentals = auth()->user()->rentals()->where('end_date', '>=', now())->count();
        // $totalSpent = auth()->user()->rentals()->sum('total_price');

        return view('user-profile', compact('totalRentals', 'activeRentals', 'totalSpent'));
    })->name('user.profile');

    // Profiel updaten
    Route::put('/profile', function () {
        // TODO: Implementeer profiel update logica
        // $validated = request()->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email,' . auth()->id(),
        //     'phone' => 'required',
        //     'address' => 'required',
        //     'current_password' => 'nullable|required_with:new_password',
        //     'new_password' => 'nullable|min:8|confirmed',
        // ]);
        //
        // $user = auth()->user();
        // $user->update($validated);
        //
        // if (request('new_password')) {
        //     if (!Hash::check(request('current_password'), $user->password)) {
        //         return back()->withErrors(['current_password' => 'Huidig wachtwoord is onjuist']);
        //     }
        //     $user->update(['password' => Hash::make(request('new_password'))]);
        // }

        return back()->with('success', 'Profiel bijgewerkt! (Demo mode)');
    })->name('user.profile.update');

    // Huurgeschiedenis
    Route::get('/history', function () {
        // Dummy data voor nu
        $rentals = collect([]);

        // TODO: Vervang met echte data
        // $rentals = auth()->user()->rentals()
        //     ->with('vehicle')
        //     ->orderBy('created_at', 'desc')
        //     ->get()
        //     ->map(function($rental) {
        //         if (now()->between($rental->start_date, $rental->end_date)) {
        //             $rental->status = 'active';
        //         } elseif (now()->lt($rental->start_date)) {
        //             $rental->status = 'upcoming';
        //         } else {
        //             $rental->status = 'completed';
        //         }
        //         return $rental;
        //     });

        return view('user-history', compact('rentals'));
    })->name('user.history');

    // Reservering maken
    Route::post('/rentals', function () {
        // TODO: Implementeer reservering logica
        // $validated = request()->validate([
        //     'vehicle_id' => 'required|exists:vehicles,id',
        //     'start_date' => 'required|date|after_or_equal:today',
        //     'end_date' => 'required|date|after:start_date',
        // ]);
        //
        // $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        // $days = Carbon::parse($validated['start_date'])->diffInDays($validated['end_date']);
        // $totalPrice = $days * $vehicle->price_per_day;
        //
        // // Check overlap (US19)
        // $hasOverlap = Rental::where('vehicle_id', $vehicle->id)
        //     ->where(function($q) use ($validated) {
        //         $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
        //           ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
        //           ->orWhere(function($q2) use ($validated) {
        //               $q2->where('start_date', '<=', $validated['start_date'])
        //                  ->where('end_date', '>=', $validated['end_date']);
        //           });
        //     })->exists();
        //
        // if ($hasOverlap) {
        //     return back()->with('error', 'Dit voertuig is al gereserveerd in deze periode');
        // }
        //
        // Rental::create([
        //     'user_id' => auth()->id(),
        //     'vehicle_id' => $vehicle->id,
        //     'start_date' => $validated['start_date'],
        //     'end_date' => $validated['end_date'],
        //     'total_price' => $totalPrice,
        // ]);

        return back()->with('success', 'Voertuig gereserveerd! (Demo mode)');
    })->name('rentals.store');

    // Reservering annuleren
    Route::delete('/rentals/{id}', function ($id) {
        // TODO: Implementeer annulering logica
        // $rental = Rental::where('id', $id)
        //     ->where('user_id', auth()->id())
        //     ->firstOrFail();
        //
        // // Check of reservering nog geannuleerd kan worden
        // if ($rental->start_date < now()) {
        //     return back()->with('error', 'Je kunt geen actieve of verlopen reserveringen annuleren');
        // }
        //
        // $rental->delete();

        return back()->with('success', 'Reservering geannuleerd (Demo mode)');
    })->name('rentals.cancel');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Deze routes zijn alleen toegankelijk voor gebruikers met admin rol
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', function () {
        // Dummy statistieken voor nu
        $totalVehicles = 0;
        $activeRentals = 0;
        $totalUsers = 0;
        $totalRevenue = 0;
        $vehicles = collect([]);

        // Dummy recent activities
        $recentActivities = [
            [
                'icon' => '🚗',
                'title' => 'Demo Activiteit',
                'description' => 'Vervang met echte data uit database',
                'time' => 'Zojuist'
            ],
        ];

        // TODO: Vervang met echte data
        // $totalVehicles = Vehicle::count();
        // $activeRentals = Rental::where('end_date', '>=', now())->count();
        // $totalUsers = User::where('role', 'user')->count();
        // $totalRevenue = Rental::sum('total_price');
        // $vehicles = Vehicle::latest()->paginate(10);

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

    // Voertuig toevoegen - verwerken
    Route::post('/vehicles', function () {
        // TODO: Implementeer voertuig toevoegen logica
        // $validated = request()->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'category' => 'required|in:personenauto,bestelbus,verhuisbus,aanhangwagen',
        //     'price_per_day' => 'required|numeric|min:0',
        //     'region' => 'required|string',
        //     'transmission' => 'required|in:automaat,schakel',
        //     'image' => 'nullable|image|max:2048',
        //     'availability_start' => 'nullable|date',
        //     'availability_end' => 'nullable|date|after:availability_start',
        // ]);
        //
        // if (request()->hasFile('image')) {
        //     $validated['image'] = request()->file('image')->store('vehicles', 'public');
        // }
        //
        // Vehicle::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Voertuig toegevoegd! (Demo mode)');
    })->name('vehicles.store');

    // Voertuig bewerken - formulier
    Route::get('/vehicles/{id}/edit', function ($id) {
        // Dummy vehicle voor nu
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

        // TODO: Vervang met echte data
        // $vehicle = Vehicle::findOrFail($id);

        return view('admin-vehicle-form', compact('vehicle'));
    })->name('vehicles.edit');

    // Voertuig updaten
    Route::put('/vehicles/{id}', function ($id) {
        // TODO: Implementeer voertuig update logica
        // $vehicle = Vehicle::findOrFail($id);
        //
        // $validated = request()->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'category' => 'required|in:personenauto,bestelbus,verhuisbus,aanhangwagen',
        //     'price_per_day' => 'required|numeric|min:0',
        //     'region' => 'required|string',
        //     'transmission' => 'required|in:automaat,schakel',
        //     'image' => 'nullable|image|max:2048',
        //     'availability_start' => 'nullable|date',
        //     'availability_end' => 'nullable|date|after:availability_start',
        // ]);
        //
        // if (request()->hasFile('image')) {
        //     if ($vehicle->image) {
        //         Storage::disk('public')->delete($vehicle->image);
        //     }
        //     $validated['image'] = request()->file('image')->store('vehicles', 'public');
        // }
        //
        // $vehicle->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Voertuig bijgewerkt! (Demo mode)');
    })->name('vehicles.update');

    // Voertuig verwijderen
    Route::delete('/vehicles/{id}', function ($id) {
        // TODO: Implementeer voertuig verwijderen logica
        // $vehicle = Vehicle::findOrFail($id);
        //
        // // Check of er actieve reserveringen zijn
        // if ($vehicle->rentals()->where('end_date', '>=', now())->exists()) {
        //     return back()->with('error', 'Kan geen voertuig verwijderen met actieve reserveringen');
        // }
        //
        // if ($vehicle->image) {
        //     Storage::disk('public')->delete($vehicle->image);
        // }
        //
        // $vehicle->delete();

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
