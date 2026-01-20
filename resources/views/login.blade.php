@extends('layout')

@section('title', 'Inloggen - AutoRental')

@section('extra-css')
    <style>
        .auth-container {
            max-width: 500px;
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

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--accent-amber);
        }

        .checkbox-wrapper label {
            margin: 0;
            cursor: pointer;
            color: var(--text-gray);
        }

        .forgot-link {
            color: var(--accent-amber);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--accent-amber-light);
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            margin-bottom: 1rem;
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

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 2rem 0;
            color: var(--text-gray);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.9rem;
        }
    </style>
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welkom Terug</h1>
                <p>Log in om verder te gaan</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="jouw@email.nl" required autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••"
                        required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Onthoud mij</label>
                    </div>
                    <a href="#" class="forgot-link">Wachtwoord vergeten?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Inloggen</button>
            </form>

            <div class="auth-footer">
                <p>Nog geen account? <a href="{{ route('register') }}">Registreer nu</a></p>
            </div>
        </div>
    </div>
@endsection
