<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AutoRental - Premium Voertuigverhuur')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Bebas+Neue&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1a1f2e;
            --secondary-dark: #2d3748;
            --accent-amber: #f59e0b;
            --accent-amber-light: #fbbf24;
            --text-light: #f7fafc;
            --text-gray: #cbd5e0;
            --border-color: #4a5568;
            --card-bg: #252d3d;
            --success: #10b981;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--primary-dark) 0%, #0f1419 100%);
            color: var(--text-light);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Navigation */
        .navbar {
            background: rgba(26, 31, 46, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: slideDown 0.6s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 2px;
            color: var(--accent-amber);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo::before {
            content: '🚗';
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-amber);
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--accent-amber);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: var(--accent-amber);
            color: var(--primary-dark);
        }

        .btn-primary:hover {
            background: var(--accent-amber-light);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-light);
            border: 2px solid var(--accent-amber);
        }

        .btn-secondary:hover {
            background: var(--accent-amber);
            color: var(--primary-dark);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 10px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: var(--accent-amber);
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        /* Mobile Menu */
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                right: -100%;
                flex-direction: column;
                background: rgba(26, 31, 46, 0.98);
                padding: 2rem;
                border-radius: 12px 0 0 12px;
                box-shadow: -5px 0 20px rgba(0, 0, 0, 0.5);
                transition: right 0.4s ease;
                border-left: 2px solid var(--accent-amber);
            }

            .nav-links.active {
                right: 0;
            }
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s ease;
            border-left: 4px solid;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success);
            color: var(--success);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger);
            color: var(--danger);
        }

        .alert-info {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--accent-amber);
            color: var(--accent-amber);
        }

        /* Footer */
        footer {
            background: var(--primary-dark);
            border-top: 1px solid var(--border-color);
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            color: var(--accent-amber);
            margin-bottom: 1rem;
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 1px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: var(--text-gray);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--accent-amber);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }

        .mt-1 {
            margin-top: 0.5rem;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .mt-3 {
            margin-top: 1.5rem;
        }

        .mt-4 {
            margin-top: 2rem;
        }

        .mb-1 {
            margin-bottom: 0.5rem;
        }

        .mb-2 {
            margin-bottom: 1rem;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 2rem;
        }
    </style>
    @yield('extra-css')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo">AutoRental</a>

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}">Home</a></li>
                {{-- <li><a href="{{ route('vehicles.index') }}">Voertuigen</a></li> --}}

                @auth
                    @if (auth()->user()->role === 'admin')
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('user.profile') }}">Profiel</a></li>
                        <li><a href="{{ route('user.history') }}">Geschiedenis</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Uitloggen</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-secondary">Inloggen</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-primary">Registreren</a></li>
                @endauth
            </ul>

            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Over Ons</h3>
                <p style="color: var(--text-gray);">AutoRental biedt premium voertuigverhuur in heel Nederland.
                    Betrouwbaar, betaalbaar en altijd beschikbaar.</p>
            </div>
            <div class="footer-section">
                <h3>Navigatie</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('vehicles.index') }}">Voertuigen</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Over Ons</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Service</h3>
                <ul>
                    <li><a href="#">Veelgestelde Vragen</a></li>
                    <li><a href="#">Voorwaarden</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Klantenservice</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>Email: info@autorental.nl</li>
                    <li>Telefoon: +31 20 123 4567</li>
                    <li>Ma-Vr: 08:00 - 18:00</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 AutoRental. Alle rechten voorbehouden.</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            }
        });
    </script>
    @yield('extra-js')
</body>

</html>
