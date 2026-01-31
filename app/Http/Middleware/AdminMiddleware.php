<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * DOEL: Check of gebruiker admin is
     * Als NIET admin → redirect naar home met error
     * Als WEL admin → ga door naar de pagina
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check 1: Is gebruiker überhaupt ingelogd?
        // (Dit zou niet nodig moeten zijn want 'auth' middleware komt eerst,
        //  maar dubbele check kan geen kwaad)
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Je moet ingelogd zijn om deze pagina te bekijken.');
        }

        // Check 2: Is de ingelogde gebruiker een admin?
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            // Niet admin? → Terug naar home met foutmelding
            return redirect()
                ->route('home')
                ->with('error', '⛔ Je hebt geen toegang tot het beheerdersgedeelte.');
        }

        // Alles oké! Gebruiker is admin, laat door
        return $next($request);
    }
}
