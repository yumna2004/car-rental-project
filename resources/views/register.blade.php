@extends('layout')

@section('title', 'Registreren - AutoRental')

@section('extra-css')
    <style>
        .auth-container {
            max-width: 600px;
            margin: 4rem auto;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-amber), var(--accent-amber-light));
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            color: var(--accent-amber);
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .auth-header p {
            color: var(--text-gray);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            background: var(--primary-dark);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-gray);
            opacity: 0.5;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-gray);
        }

        .auth-footer a {
            color: var(--accent-amber);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .auth-footer a:hover {
            color: var(--accent-amber-light);
        }

        .terms-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .terms-wrapper input[type="checkbox"] {
            margin-top: 0.25rem;
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--accent-amber);
            flex-shrink: 0;
        }

        .terms-wrapper label {
            margin: 0;
            cursor: pointer;
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .terms-wrapper label a {
            color: var(--accent-amber);
            text-decoration: none;
        }

        .terms-wrapper label a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .auth-card {
                padding: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Account Aanmaken</h1>
                <p>Start vandaag nog met huren</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Volledige Naam</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Jan de Vries" required autofocus>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefoonnummer</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}"
                            placeholder="06 12345678" required>
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="jouw@email.nl" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Adres</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}"
                        placeholder="Straatnaam 123, 1234 AB Plaats" required>
                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Wachtwoord</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Minimaal 8 tekens" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Bevestig Wachtwoord</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            placeholder="Herhaal wachtwoord" required>
                    </div>
                </div>

                <div class="terms-wrapper">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">
                        Ik ga akkoord met de <a href="#">algemene voorwaarden</a> en het <a
                            href="#">privacybeleid</a> van AutoRental.
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Account Aanmaken</button>
            </form>

            <div class="auth-footer">
                <p>Al een account? <a href="{{ route('login') }}">Log hier in</a></p>
            </div>
        </div>
    </div>
@endsection
