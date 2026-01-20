<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Gebruiker kan inloggen met geldige gegevens
     * 
     * Deze test controleert of een normale gebruiker:
     * 1. Kan inloggen met correcte email en wachtwoord
     * 2. Wordt doorgestuurd naar hun profiel pagina
     * 3. Een succesbericht ziet
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        // ARRANGE: Maak een test gebruiker aan
        $user = User::create([
            'name' => 'Jan de Vries',
            'email' => 'jan@test.nl',
            'password' => Hash::make('wachtwoord123'),
            'phone' => '0612345678',
            'address' => 'Teststraat 123',
            'role' => 'user',
        ]);

        // ACT: Probeer in te loggen
        $response = $this->post('/login', [
            'email' => 'jan@test.nl',
            'password' => 'wachtwoord123',
        ]);

        // ASSERT: Check of login succesvol was
        $response->assertRedirect('/profile'); // Redirect naar user profile
        $this->assertAuthenticatedAs($user); // Check of user is ingelogd
        $response->assertSessionHas('success'); // Check of succesbericht bestaat
    }

    /**
     * Test: Admin wordt doorgestuurd naar admin dashboard
     * 
     * Deze test controleert of een admin gebruiker:
     * 1. Kan inloggen met correcte gegevens
     * 2. Wordt doorgestuurd naar admin dashboard (niet user profile)
     */
    public function test_admin_can_login_and_redirects_to_admin_dashboard(): void
    {
        // ARRANGE: Maak een admin gebruiker aan
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.nl',
            'password' => Hash::make('admin123'),
            'phone' => '0687654321',
            'address' => 'Adminstraat 1',
            'role' => 'admin',
        ]);

        // ACT: Probeer in te loggen als admin
        $response = $this->post('/login', [
            'email' => 'admin@test.nl',
            'password' => 'admin123',
        ]);

        // ASSERT: Check of admin naar admin dashboard gaat
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
        $response->assertSessionHas('success');
    }

    /**
     * Test: Login mislukt met verkeerde gegevens
     * 
     * Deze test controleert of:
     * 1. Login wordt geweigerd bij verkeerd wachtwoord
     * 2. Gebruiker niet wordt ingelogd
     * 3. Foutmelding wordt getoond
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        // ARRANGE: Maak een test gebruiker aan
        $user = User::create([
            'name' => 'Jan de Vries',
            'email' => 'jan@test.nl',
            'password' => Hash::make('wachtwoord123'),
            'phone' => '0612345678',
            'address' => 'Teststraat 123',
            'role' => 'user',
        ]);

        // ACT: Probeer in te loggen met VERKEERD wachtwoord
        $response = $this->post('/login', [
            'email' => 'jan@test.nl',
            'password' => 'verkeerd_wachtwoord',
        ]);

        // ASSERT: Check of login mislukte
        $response->assertRedirect(); // Redirect terug naar login pagina
        $this->assertGuest(); // Check of gebruiker NIET is ingelogd
        $response->assertSessionHasErrors('email'); // Check of foutmelding bestaat
    }

    /**
     * Test: Login mislukt met niet-bestaand email adres
     */
    public function test_user_cannot_login_with_non_existent_email(): void
    {
        // ACT: Probeer in te loggen met email die niet bestaat
        $response = $this->post('/login', [
            'email' => 'nietbestaand@test.nl',
            'password' => 'wachtwoord123',
        ]);

        // ASSERT: Check of login mislukte
        $response->assertRedirect();
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test: Validatie werkt - email is verplicht
     */
    public function test_email_is_required(): void
    {
        // ACT: Probeer in te loggen zonder email
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'wachtwoord123',
        ]);

        // ASSERT: Check validatiefout
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test: Validatie werkt - wachtwoord is verplicht
     */
    public function test_password_is_required(): void
    {
        // ACT: Probeer in te loggen zonder wachtwoord
        $response = $this->post('/login', [
            'email' => 'jan@test.nl',
            'password' => '',
        ]);

        // ASSERT: Check validatiefout
        $response->assertSessionHasErrors('password');
    }

    /**
     * Test: Login pagina is toegankelijk
     */
    public function test_login_page_can_be_displayed(): void
    {
        // ACT: Bezoek de login pagina
        $response = $this->get('/login');

        // ASSERT: Check of pagina laadt
        $response->assertStatus(200);
        $response->assertViewIs('login'); // Check of juiste view wordt gebruikt
    }
}
