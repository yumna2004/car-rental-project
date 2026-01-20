@extends('layout')

@section('title', 'AutoRental - Premium Voertuigverhuur')

@section('extra-css')
    <style>
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
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

        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3.5rem;
            color: var(--accent-amber);
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .page-header p {
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        /* Filter Section */
        .filters-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 3rem;
            animation: slideIn 0.8s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .filters-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            color: var(--accent-amber);
            letter-spacing: 1px;
        }

        .filter-reset {
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .filter-reset:hover {
            color: var(--accent-amber);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .filter-select,
        .filter-input {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary-dark);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: var(--accent-amber);
        }

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .filter-actions .btn {
            padding: 0.75rem 2rem;
        }

        /* Vehicle Grid */
        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .vehicle-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .vehicle-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-amber);
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.2);
        }

        .vehicle-image {
            height: 220px;
            background: linear-gradient(135deg, var(--secondary-dark), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }

        .vehicle-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .vehicle-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent-amber);
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .vehicle-content {
            padding: 1.5rem;
        }

        .vehicle-category {
            color: var(--accent-amber);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .vehicle-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            color: var(--text-light);
            margin-bottom: 0.75rem;
            letter-spacing: 1px;
        }

        .vehicle-info {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        .info-item span:first-child {
            font-size: 1.1rem;
        }

        .vehicle-description {
            color: var(--text-gray);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .vehicle-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .vehicle-price {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--accent-amber);
            letter-spacing: 1px;
        }

        .vehicle-price small {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: var(--text-gray);
            font-weight: 400;
        }

        .vehicle-btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.9rem;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .no-results-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .no-results h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .no-results p {
            color: var(--text-gray);
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .vehicles-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .filter-actions .btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1>Onze Voertuigen</h1>
        <p>Kies uit ons diverse aanbod van voertuigen</p>
    </div>

    <!-- Filters -->
    <div class="filters-container">
        <div class="filters-header">
            <h2>🔍 Filter Voertuigen</h2>
            <a href="{{ route('vehicles.index') }}" class="filter-reset">Reset filters</a>
        </div>

        <form method="GET" action="{{ route('vehicles.index') }}">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="category">Categorie</label>
                    <select name="category" id="category" class="filter-select">
                        <option value="">Alle categorieën</option>
                        <option value="personenauto" {{ request('category') == 'personenauto' ? 'selected' : '' }}>
                            Personenauto</option>
                        <option value="bestelbus" {{ request('category') == 'bestelbus' ? 'selected' : '' }}>Bestelbus
                        </option>
                        <option value="verhuisbus" {{ request('category') == 'verhuisbus' ? 'selected' : '' }}>Verhuisbus
                        </option>
                        <option value="aanhangwagen" {{ request('category') == 'aanhangwagen' ? 'selected' : '' }}>
                            Aanhangwagen</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="transmission">Transmissie</label>
                    <select name="transmission" id="transmission" class="filter-select">
                        <option value="">Alle types</option>
                        <option value="automaat" {{ request('transmission') == 'automaat' ? 'selected' : '' }}>Automaat
                        </option>
                        <option value="schakel" {{ request('transmission') == 'schakel' ? 'selected' : '' }}>Schakel
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="region">Regio</label>
                    <select name="region" id="region" class="filter-select">
                        <option value="">Alle regio's</option>
                        <option value="Amsterdam" {{ request('region') == 'Amsterdam' ? 'selected' : '' }}>Amsterdam
                        </option>
                        <option value="Rotterdam" {{ request('region') == 'Rotterdam' ? 'selected' : '' }}>Rotterdam
                        </option>
                        <option value="Utrecht" {{ request('region') == 'Utrecht' ? 'selected' : '' }}>Utrecht</option>
                        <option value="Den Haag" {{ request('region') == 'Den Haag' ? 'selected' : '' }}>Den Haag</option>
                        <option value="Eindhoven" {{ request('region') == 'Eindhoven' ? 'selected' : '' }}>Eindhoven
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="max_price">Max. Prijs per dag</label>
                    <input type="number" name="max_price" id="max_price" class="filter-input" placeholder="€ 100"
                        value="{{ request('max_price') }}" min="0">
                </div>

                <div class="filter-group">
                    <label for="start_date">Vanaf Datum</label>
                    <input type="date" name="start_date" id="start_date" class="filter-input"
                        value="{{ request('start_date') }}">
                </div>

                <div class="filter-group">
                    <label for="end_date">Tot Datum</label>
                    <input type="date" name="end_date" id="end_date" class="filter-input"
                        value="{{ request('end_date') }}">
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">🔍 Zoeken</button>
            </div>
        </form>
    </div>

    <!-- Vehicles Grid -->
    @if (isset($vehicles) && $vehicles->count() > 0)
        <div class="vehicles-grid">
            @foreach ($vehicles as $vehicle)
                <div class="vehicle-card" onclick="window.location.href='{{ route('vehicles.show', $vehicle->id) }}'">
                    <div class="vehicle-image">
                        @if ($vehicle->image)
                            <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->title }}">
                        @else
                            {{ ['🚗', '🚙', '🚐', '🚚'][array_rand([0, 1, 2, 3])] }}
                        @endif
                        <div class="vehicle-badge">Beschikbaar</div>
                    </div>

                    <div class="vehicle-content">
                        <div class="vehicle-category">{{ ucfirst($vehicle->category) }}</div>
                        <h3 class="vehicle-title">{{ $vehicle->title }}</h3>

                        <div class="vehicle-info">
                            <div class="info-item">
                                <span>📍</span>
                                <span>{{ $vehicle->region }}</span>
                            </div>
                            <div class="info-item">
                                <span>⚙️</span>
                                <span>{{ ucfirst($vehicle->transmission) }}</span>
                            </div>
                        </div>

                        <p class="vehicle-description">{{ $vehicle->description }}</p>

                        <div class="vehicle-footer">
                            <div class="vehicle-price">
                                €{{ number_format($vehicle->price_per_day, 2) }}
                                <small>/dag</small>
                            </div>
                            <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-primary vehicle-btn">Bekijk
                                →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h3>Geen voertuigen gevonden</h3>
            <p>Probeer andere filterinstellingen of bekijk alle voertuigen</p>
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary">Toon Alle Voertuigen</a>
        </div>
    @endif

    <!-- Pagination -->
    @if (isset($vehicles) && $vehicles->hasPages())
        <div style="margin-top: 3rem;">
            {{ $vehicles->links() }}
        </div>
    @endif
@endsection
