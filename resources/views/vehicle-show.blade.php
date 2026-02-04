@extends('layout')

@section('title', $vehicle->title . ' - AutoRental')

@section('extra-css')
    <style>
        .vehicle-detail {
            max-width: 1200px;
            margin: 2rem auto;
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

        .breadcrumb {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: var(--accent-amber);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: var(--accent-amber-light);
        }

        .vehicle-header {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .vehicle-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-amber), var(--accent-amber-light));
        }

        .vehicle-category-badge {
            display: inline-block;
            background: var(--accent-amber);
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .vehicle-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            letter-spacing: 2px;
        }

        .vehicle-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            color: var(--text-gray);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
        }

        .meta-item span:first-child {
            font-size: 1.3rem;
        }

        .vehicle-main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .vehicle-gallery {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }

        .main-image {
            height: 500px;
            background: linear-gradient(135deg, var(--secondary-dark), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
        }

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .vehicle-sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .price-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
            position: sticky;
            top: 100px;
        }

        .price-amount {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3.5rem;
            color: var(--accent-amber);
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .price-period {
            color: var(--text-gray);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
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

        .total-price {
            padding: 1.5rem;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 8px;
            border: 1px solid var(--accent-amber);
        }

        .total-price-label {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .total-price-amount {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--accent-amber);
            letter-spacing: 1px;
        }

        .btn-book {
            width: 100%;
            padding: 1.25rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .vehicle-details {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem;
        }

        .details-section {
            margin-bottom: 2rem;
        }

        .details-section:last-child {
            margin-bottom: 0;
        }

        .details-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--accent-amber);
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .details-section p {
            color: var(--text-gray);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .spec-item {
            background: var(--primary-dark);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .spec-item:hover {
            border-color: var(--accent-amber);
            transform: translateY(-2px);
        }

        .spec-label {
            color: var(--text-gray);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .spec-value {
            color: var(--text-light);
            font-size: 1.1rem;
            font-weight: 600;
        }

        .reserved-badge {
            background: rgba(245, 158, 11, 0.2);
            border: 1px solid var(--accent-amber);
            color: var(--text-light);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .reserved-info {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            color: var(--accent-amber);
        }

        @media (max-width: 1024px) {
            .vehicle-main {
                grid-template-columns: 1fr;
            }

            .price-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            .vehicle-title {
                font-size: 2rem;
            }

            .main-image {
                height: 300px;
                font-size: 5rem;
            }

            .price-amount {
                font-size: 2.5rem;
            }

            .specs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="vehicle-detail">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('vehicles.index') }}">Voertuigen</a>
            <span>/</span>
            <span>{{ $vehicle->title }}</span>
        </nav>

        <!-- Vehicle Header -->
        <div class="vehicle-header">
            <div class="vehicle-category-badge">{{ ucfirst($vehicle->category) }}</div>
            <h1 class="vehicle-title">{{ $vehicle->title }}</h1>
            <div class="vehicle-meta">
                <div class="meta-item">
                    <span>📍</span>
                    <span>{{ $vehicle->region }}</span>
                </div>
                <div class="meta-item">
                    <span>⚙️</span>
                    <span>{{ ucfirst($vehicle->transmission) }}</span>
                </div>
                <div class="meta-item">
                    <span>📅</span>
                    <span>Beschikbaar</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="vehicle-main">
            <!-- Gallery -->
            <div class="vehicle-gallery">
                <div class="main-image">
                    @if ($vehicle->image)
                        <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->title }}">
                    @else
                        🚗
                    @endif
                </div>
            </div>

            <!-- Sidebar - Booking -->
            <div class="vehicle-sidebar">
                <div class="price-card">
                    <div class="price-amount">€{{ number_format($vehicle->price_per_day, 2) }}</div>
                    <div class="price-period">per dag</div>

                    @guest
                        <div class="alert alert-info">
                            <strong>Let op:</strong> Je moet ingelogd zijn om te reserveren.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-book">Inloggen om te Reserveren</a>
                        <a href="{{ route('register') }}" class="btn btn-secondary"
                            style="width: 100%; margin-top: 1rem;">Account Aanmaken</a>
                    @else
                        {{-- Info badge als voertuig NU bezet is --}}
                        @if (isset($isReserved) && $isReserved)
                            <div class="reserved-badge">
                                ℹ️ Momenteel Bezet
                                <div class="reserved-info">
                                    Beschikbaar vanaf: {{ $reservationEndDate ?? 'binnenkort' }}
                                </div>
                                <div style="font-size: 0.85rem; margin-top: 0.5rem; opacity: 0.8;">
                                    💡 Tip: Je kunt wel reserveren voor latere datums
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('vehicles.rent', $vehicle->id) }}" class="booking-form">
                            @csrf

                            <div class="form-group">
                                <label for="start_date">Startdatum</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required
                                    min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <span
                                        style="color: #ef4444; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="end_date">Einddatum</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required
                                    min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <span
                                        style="color: #ef4444; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="total-price" id="totalPriceBox" style="display: none;">
                                <div class="total-price-label">Totale Prijs</div>
                                <div class="total-price-amount" id="totalPrice">€0.00</div>
                                <div style="color: var(--text-gray); font-size: 0.85rem; margin-top: 0.5rem;" id="daysCount">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-book">
                                ✓ Reserveren
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Vehicle Details -->
        <div class="vehicle-details">
            <div class="details-section">
                <h2>📝 Beschrijving</h2>
                <p>{{ $vehicle->description }}</p>
            </div>

            <div class="details-section">
                <h2>📊 Specificaties</h2>
                <div class="specs-grid">
                    <div class="spec-item">
                        <div class="spec-label">Categorie</div>
                        <div class="spec-value">{{ ucfirst($vehicle->category) }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Transmissie</div>
                        <div class="spec-value">{{ ucfirst($vehicle->transmission) }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Locatie</div>
                        <div class="spec-value">{{ $vehicle->region }}</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-label">Prijs per dag</div>
                        <div class="spec-value">€{{ number_format($vehicle->price_per_day, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        const pricePerDay = {{ $vehicle->price_per_day }};
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const totalPriceBox = document.getElementById('totalPriceBox');
        const totalPriceElement = document.getElementById('totalPrice');
        const daysCountElement = document.getElementById('daysCount');

        function calculateTotal() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (startDate && endDate && endDate > startDate) {
                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                const total = days * pricePerDay;

                totalPriceElement.textContent = '€' + total.toFixed(2);
                daysCountElement.textContent = days + ' dag' + (days > 1 ? 'en' : '');
                totalPriceBox.style.display = 'block';
            } else {
                totalPriceBox.style.display = 'none';
            }
        }

        startDateInput.addEventListener('change', calculateTotal);
        endDateInput.addEventListener('change', calculateTotal);

        // Set min date for end date based on start date
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
        });
    </script>
@endsection
