@extends('layout')

@section('title', 'Mijn Geschiedenis - AutoRental')

@section('extra-css')
    <style>
        .history-container {
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

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            color: var(--accent-amber);
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
        }

        .page-header p {
            color: var(--text-gray);
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-gray);
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 500;
        }

        .tab-btn:hover {
            border-color: var(--accent-amber);
            color: var(--accent-amber);
        }

        .tab-btn.active {
            background: var(--accent-amber);
            border-color: var(--accent-amber);
            color: var(--primary-dark);
        }

        .rentals-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .rental-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.4s ease;
            animation: slideIn 0.6s ease;
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

        .rental-card:hover {
            border-color: var(--accent-amber);
            transform: translateX(5px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.15);
        }

        .rental-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .rental-vehicle {
            flex: 1;
        }

        .rental-vehicle h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.75rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .rental-category {
            color: var(--accent-amber);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .rental-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .status-upcoming {
            background: rgba(245, 158, 11, 0.2);
            color: var(--accent-amber);
            border: 1px solid var(--accent-amber);
        }

        .status-completed {
            background: rgba(203, 213, 224, 0.2);
            color: var(--text-gray);
            border: 1px solid var(--text-gray);
        }

        .rental-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .detail-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .detail-content label {
            display: block;
            color: var(--text-gray);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-content span {
            color: var(--text-light);
            font-size: 1.05rem;
            font-weight: 500;
        }

        .rental-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .rental-price {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--accent-amber);
            letter-spacing: 1px;
        }

        .rental-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .no-rentals {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .no-rentals-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .no-rentals h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .no-rentals p {
            color: var(--text-gray);
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .rental-header {
                flex-direction: column;
                gap: 1rem;
            }

            .rental-details {
                grid-template-columns: 1fr;
            }

            .rental-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .rental-actions {
                width: 100%;
                flex-direction: column;
            }

            .rental-actions .btn {
                width: 100%;
            }

            .filter-tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
            }

            .btn.btn-danger.btn-small {
            padding: 13px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="history-container">
        <div class="page-header">
            <h1>Mijn Reserveringen</h1>
            <p>Overzicht van al je huurgeschiedenis</p>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="tab-btn active" data-filter="all">🔍 Alle Reserveringen</button>
            <button class="tab-btn" data-filter="upcoming">📅 Binnenkort</button>
            <button class="tab-btn" data-filter="active">⚡ Actief</button>
            <button class="tab-btn" data-filter="completed">✅ Voltooid</button>
        </div>

        <!-- Rentals List -->
        @if (isset($rentals) && $rentals->count() > 0)
            <div class="rentals-list">
                @foreach ($rentals as $rental)
                    @php
                        $today = \Carbon\Carbon::today();
                        $startDate = \Carbon\Carbon::parse($rental->start_date);
                        $endDate = \Carbon\Carbon::parse($rental->end_date);

                        // Bepaal status
                        if ($endDate->lt($today)) {
                            $status = 'completed';
                        } elseif ($startDate->lte($today) && $endDate->gte($today)) {
                            $status = 'active';
                        } else {
                            $status = 'upcoming';
                        }
                    @endphp

                    <div class="rental-card" data-status="{{ $status }}">
                        <div class="rental-header">
                            <div class="rental-vehicle">
                                <div class="rental-category">{{ ucfirst($rental->vehicle->category) }}</div>
                                <h3>{{ $rental->vehicle->title }}</h3>
                            </div>
                            <div class="rental-status status-{{ $status }}">
                                @if ($status === 'active')
                                    ⚡ Actief
                                @elseif($status === 'upcoming')
                                    📅 Binnenkort
                                @else
                                    ✅ Voltooid
                                @endif
                            </div>
                        </div>

                        <div class="rental-details">
                            <div class="detail-item">
                                <div class="detail-icon">📍</div>
                                <div class="detail-content">
                                    <label>Locatie</label>
                                    <span>{{ $rental->vehicle->region }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">📅</div>
                                <div class="detail-content">
                                    <label>Startdatum</label>
                                    <span>{{ $startDate->format('d-m-Y') }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">🏁</div>
                                <div class="detail-content">
                                    <label>Einddatum</label>
                                    <span>{{ $endDate->format('d-m-Y') }}</span>
                                </div>
                            </div>

                            <div class="detail-item">
                                <div class="detail-icon">⏱️</div>
                                <div class="detail-content">
                                    <label>Duur</label>
                                    <span>{{ $startDate->diffInDays($endDate) + 1 }} dagen</span>
                                </div>
                            </div>
                        </div>

                        <div class="rental-footer">
                            <div class="rental-price">
                                €{{ number_format($rental->total_price, 2) }}
                            </div>
                            <div class="rental-actions">
                                <a href="{{ route('vehicles.show', $rental->vehicle->id) }}"
                                    class="btn btn-secondary btn-small">
                                    🚗 Voertuig Bekijken
                                </a>
                                @if ($status === 'upcoming')
                                    <form method="POST" action="{{ route('rentals.cancel', $rental->id) }}"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-small"
                                            onclick="return confirm('Weet je zeker dat je deze reservering wilt annuleren?')">
                                            Annuleren
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-rentals">
                <div class="no-rentals-icon">🚗</div>
                <h3>Nog Geen Reserveringen</h3>
                <p>Je hebt nog geen voertuigen gehuurd. Begin nu met browsen!</p>
                <a href="{{ route('vehicles.index') }}" class="btn btn-primary">Bekijk Voertuigen</a>
            </div>
        @endif
    </div>
@endsection

@section('extra-js')
    <script>
        // Filter functionality
        const tabButtons = document.querySelectorAll('.tab-btn');
        const rentalCards = document.querySelectorAll('.rental-card');

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active tab
                tabButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Filter cards
                const filter = btn.dataset.filter;
                rentalCards.forEach(card => {
                    if (filter === 'all') {
                        card.style.display = 'block';
                    } else {
                        card.style.display = card.dataset.status === filter ? 'block' : 'none';
                    }
                });
            });
        });
    </script>
@endsection
