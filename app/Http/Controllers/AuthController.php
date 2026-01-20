<?php

namespace App\Http\Controllers;

// Imports - de "tools" die we nodig hebben
use Illuminate\Http\Request;              // Voor HTTP requests
use Illuminate\Support\Facades\Auth;      // Voor login/logout
use Illuminate\Support\Facades\Hash;      // Voor wachtwoord hashing
use App\Models\User;                       // Ons User model

class AuthController extends Controller
{
    /**
     * ═══════════════════════════════════════════════════
     * REGISTRATIE
     * ═══════════════════════════════════════════════════
     */

    /**
     * Toon het registratie formulier
     * 
     * Route: GET /register
     * View: register.blade.php
     * 
     * Deze functie wordt aangeroepen als iemand naar
     * de registratie pagina gaat.
     */
    public function showRegister()
    {
        // return view('naam') → Laad een blade template
        // Laravel zoekt automatisch naar: resources/views/register.blade.php
        return view('register');
    }

    /**
     * Verwerk de registratie
     * 
     * Route: POST /register
     * 
     * Deze functie wordt aangeroepen als iemand het
     * registratie formulier indient.
     * 
     * @param Request $request - Bevat alle formulier data
     * @return RedirectResponse
     */
    public function register(Request $request)
    {
        /**
         * STAP 1: VALIDATIE
         * Check of alle velden correct zijn ingevuld
         */
        $validated = $request->validate([
            // Veldnaam => 'regel1|regel2|regel3'

            'name' => 'required|string|max:255',
            // required = verplicht veld
            // string = moet een tekst zijn
            // max:255 = maximaal 255 karakters

            'email' => 'required|email|unique:users,email',
            // required = verplicht
            // email = moet geldig email adres zijn (met @)
            // unique:users,email = email mag niet al bestaan in users tabel

            'password' => 'required|min:8|confirmed',
            // required = verplicht
            // min:8 = minimaal 8 karakters
            // confirmed = er moet een "password_confirmation" veld zijn dat hetzelfde is

            'phone' => 'required|string',
            // required = verplicht
            // string = moet tekst zijn

            'address' => 'required|string',
            // required = verplicht
            // string = moet tekst zijn

        ], [
            // Custom foutmeldingen in het Nederlands
            // 'veldnaam.regel' => 'Bericht'

            'name.required' => 'Naam is verplicht',
            'name.max' => 'Naam mag maximaal 255 tekens zijn',

            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'email.unique' => 'Dit e-mailadres is al in gebruik',

            'password.required' => 'Wachtwoord is verplicht',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens zijn',
            'password.confirmed' => 'Wachtwoorden komen niet overeen',

            'phone.required' => 'Telefoonnummer is verplicht',
            'address.required' => 'Adres is verplicht',
        ]);

        /**
         * STAP 2: MAAK GEBRUIKER AAN
         * Als validatie slaagt, maak nieuwe user in database
         */
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],

            // Hash::make() = Beveilig het wachtwoord
            // Input: "password123"
            // Output: "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"
            'password' => Hash::make($validated['password']),

            'phone' => $validated['phone'],
            'address' => $validated['address'],

            // Standaard rol is 'user'
            // (admin accounts worden handmatig gemaakt)
            'role' => 'user',
        ]);

        /**
         * STAP 3: LOG GEBRUIKER AUTOMATISCH IN
         * Na registratie hoeft de gebruiker niet opnieuw in te loggen
         */
        Auth::login($user);
        // Dit maakt een "sessie" aan - de gebruiker is nu ingelogd!

        /**
         * STAP 4: REDIRECT MET SUCCESS BERICHT
         * Stuur gebruiker naar home pagina met bevestigingsbericht
         */
        return redirect()->route('home')->with('success', 'Account succesvol aangemaakt! Welkom, ' . $user->name . '!');
        // with('success', 'bericht') = Flash message (eenmalig bericht)
        // In je blade kun je dit tonen met: @if(session('success'))
    }

    /**
     * ═══════════════════════════════════════════════════
     * LOGIN
     * ═══════════════════════════════════════════════════
     */

    /**
     * Toon het login formulier
     * 
     * Route: GET /login
     * View: login.blade.php
     */
    public function showLogin()
    {
        return view('login');
    }

    /**
     * Verwerk de login
     * 
     * Route: POST /login
     * 
     * Deze functie checkt of email + wachtwoord correct zijn
     * en logt de gebruiker in.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request)
    {
        /**
         * STAP 1: VALIDATIE
         * Check of email en wachtwoord zijn ingevuld
         */
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'E-mailadres is verplicht',
            'email.email' => 'Voer een geldig e-mailadres in',
            'password.required' => 'Wachtwoord is verplicht',
        ]);

        /**
         * STAP 2: PROBEER IN TE LOGGEN
         * 
         * Auth::attempt() doet dit automatisch:
         * 1. Zoek gebruiker met deze email in database
         * 2. Check of wachtwoord correct is (met Hash::check)
         * 3. Als correct: maak sessie aan
         * 4. Return true/false
         */
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // filled('remember') = Was "Onthoud mij" checkbox aangevinkt?

            /**
             * STAP 3: REGENEREER SESSIE (BEVEILIGING!)
             * Dit voorkomt "session fixation" attacks
             */
            $request->session()->regenerate();

            /**
             * STAP 4: CHECK ROL EN REDIRECT
             * Admin → admin dashboard
             * User → home pagina
             */
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                // Admin gebruiker
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Welkom terug, ' . $user->name . '! (Admin)');
            } else {
                // Normale gebruiker
                return redirect()
                    ->route('user.profile')
                    ->with('success', 'Welkom terug, ' . $user->name . '!');
            }
        }

        /**
         * STAP 5: LOGIN MISLUKT
         * Als email/wachtwoord incorrect zijn
         */
        return back()->withErrors([
            'email' => 'De inloggegevens zijn onjuist.',
        ])->onlyInput('email');
        // onlyInput('email') = Behoud email in formulier (niet wachtwoord!)
        // De gebruiker hoeft email niet opnieuw in te typen
    }

    /**
     * ═══════════════════════════════════════════════════
     * LOGOUT
     * ═══════════════════════════════════════════════════
     */

    /**
     * Log de gebruiker uit
     * 
     * Route: POST /logout
     * 
     * Belangrijk: Dit moet een POST request zijn (niet GET)
     * voor beveiliging (voorkomt CSRF attacks)
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        /**
         * STAP 1: LOG UIT
         * Verwijder de gebruiker uit de sessie
         */
        Auth::logout();

        /**
         * STAP 2: INVALIDATE SESSIE (BEVEILIGING!)
         * Verwijder de oude sessie ID
         */
        $request->session()->invalidate();

        /**
         * STAP 3: REGENERATE TOKEN (BEVEILIGING!)
         * Maak een nieuwe CSRF token
         */
        $request->session()->regenerateToken();

        /**
         * STAP 4: REDIRECT
         * Stuur gebruiker terug naar home pagina
         */
        return redirect()
            ->route('home')
            ->with('success', 'Je bent uitgelogd. Tot ziens!');
    }
}
