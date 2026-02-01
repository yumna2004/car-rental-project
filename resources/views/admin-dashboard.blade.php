@extends('layout')

@section('title', 'Admin Dashboard - AutoRental')

@section('extra-css')
    <style>
        .dashboard-container {
            max-width: 1400px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            color: var(--accent-amber);
            letter-spacing: 2px;
        }

        .quick-actions {
            display: flex;
            gap: 1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-amber);
            transform: scaleY(0);
            transition: transform 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-amber);
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.2);
        }

        .stat-card:hover::before {
            transform: scaleY(1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            font-size: 2.5rem;
        }

        .stat-trend {
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .trend-up {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .trend-down {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .stat-value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            color: var(--text-light);
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--accent-amber);
            letter-spacing: 1px;
        }

        /* Recent Activity */
        .activity-card,
        .vehicles-table-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--primary-dark);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            border-color: var(--accent-amber);
            transform: translateX(5px);
        }

        .activity-icon {
            font-size: 2rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-content strong {
            color: var(--text-light);
        }

        .activity-content p {
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .activity-time {
            color: var(--text-gray);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* Table */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--primary-dark);
        }

        th {
            padding: 1rem;
            text-align: left;
            color: var(--accent-amber);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 1rem;
            color: var(--text-light);
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr {
            transition: background 0.3s ease;
        }

        tbody tr:hover {
            background: var(--primary-dark);
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .vehicle-thumb {
            width: 60px;
            height: 60px;
            background: var(--primary-dark);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .vehicle-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--accent-amber);
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .quick-actions {
                width: 100%;
                flex-direction: column;
            }

            .quick-actions .btn {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .activity-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-wrapper {
                font-size: 0.85rem;
            }

            th,
            td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <div class="page-header">
            <h1>🎛️ Admin Dashboard</h1>
            <div class="quick-actions">
                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">➕ Voertuig Toevoegen</a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">🚗</div>
                    <div class="stat-trend trend-up">+12%</div>
                </div>
                <div class="stat-value">{{ $totalVehicles ?? 0 }}</div>
                <div class="stat-label">Totaal Voertuigen</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-trend trend-up">+8%</div>
                </div>
                <div class="stat-value">{{ $activeRentals ?? 0 }}</div>
                <div class="stat-label">Actieve Reserveringen</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">👥</div>
                    <div class="stat-trend trend-up">+23%</div>
                </div>
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Geregistreerde Gebruikers</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💰</div>
                    <div class="stat-trend trend-up">+15%</div>
                </div>
                <div class="stat-value">€{{ number_format($totalRevenue ?? 0, 0) }}</div>
                <div class="stat-label">Totale Omzet</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="section-header">
            <h2>📊 Recente Activiteit</h2>
        </div>

        <div class="activity-card">
            <div class="activity-list">
                @if (isset($recentActivities) && count($recentActivities) > 0)
                    @foreach ($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon">{{ $activity['icon'] }}</div>
                            <div class="activity-content">
                                <strong>{{ $activity['title'] }}</strong>
                                <p>{{ $activity['description'] }}</p>
                            </div>
                            <div class="activity-time">{{ $activity['time'] }}</div>
                        </div>
                    @endforeach
                @else
                    <div class="activity-item">
                        <div class="activity-icon">ℹ️</div>
                        <div class="activity-content">
                            <strong>Geen recente activiteit</strong>
                            <p>Er zijn nog geen activiteiten geregistreerd</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Vehicles Table -->
        <div class="section-header">
            <h2>🚗 Voertuigen Beheer</h2>
            <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-small">➕ Nieuw Voertuig</a>
        </div>

        <div class="vehicles-table-container">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Voertuig</th>
                            <th>Categorie</th>
                            <th>Regio</th>
                            <th>Prijs/Dag</th>
                            <th>Status</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($vehicles) && $vehicles->count() > 0)
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 1rem;">
                                            <div class="vehicle-thumb">
                                                @if ($vehicle->image)
                                                    <img src="{{ asset('storage/' . $vehicle->image) }}"
                                                        alt="{{ $vehicle->title }}">
                                                @else
                                                    🚗
                                                @endif
                                            </div>
                                            <strong>{{ $vehicle->title }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ ucfirst($vehicle->category) }}</td>
                                    <td>{{ $vehicle->region }}</td>
                                    <td>€{{ number_format($vehicle->price_per_day, 2) }}</td>
                                    <td>
                                        <span class="badge badge-success">Beschikbaar</span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('admin.vehicles.edit', $vehicle->id) }}"
                                                class="btn btn-secondary btn-icon">✏️</a>
                                            <form method="POST"
                                                action="{{ route('admin.vehicles.destroy', $vehicle->id) }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-icon"
                                                    onclick="return confirm('Weet je zeker dat je dit voertuig wilt verwijderen?')">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-gray); padding: 2rem;">
                                    Geen voertuigen gevonden. Voeg je eerste voertuig toe!
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
