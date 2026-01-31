@extends('layout')

@section('title', 'Mijn Profiel - AutoRental')

@section('extra-css')
    <style>
        .profile-container {
            max-width: 900px;
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

        .profile-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 3rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-amber), var(--accent-amber-light));
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--accent-amber), var(--accent-amber-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
        }

        .profile-info h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .profile-info p {
            color: var(--text-gray);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            color: var(--accent-amber);
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
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

        .form-control[readonly] {
            background: var(--secondary-dark);
            opacity: 0.7;
            cursor: not-allowed;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--primary-dark);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--accent-amber);
            transform: translateY(-5px);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            color: var(--accent-amber);
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        .stat-label {
            color: var(--text-gray);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }

            .page-header h1 {
                font-size: 2rem;
            }
        }

        /* Account Verwijderen Sectie */
        .danger-zone {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid rgba(239, 68, 68, 0.2);
        }

        .danger-card {
            background: rgba(239, 68, 68, 0.05);
            border: 2px solid rgba(239, 68, 68, 0.3);
            border-radius: 16px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .danger-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--danger), #dc2626);
        }

        .danger-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .danger-header h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.5rem;
            color: var(--danger);
            letter-spacing: 1px;
        }

        .danger-header .icon {
            font-size: 1.5rem;
        }

        .danger-description {
            color: var(--text-gray);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .danger-description strong {
            color: var(--danger);
        }

        /* Modal Styling */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: var(--card-bg);
            border: 2px solid var(--danger);
            border-radius: 16px;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            margin: 1rem;
            animation: scaleIn 0.3s ease;
            position: relative;
        }

        .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--danger), #dc2626);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            color: var(--danger);
            letter-spacing: 1px;
        }

        .modal-header .icon {
            font-size: 2rem;
        }

        .modal-body {
            margin-bottom: 2rem;
        }

        .modal-body p {
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
        }

        .modal-actions .btn {
            flex: 1;
        }

        .hidden {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="profile-container">
        <div class="page-header">
            <h1>Mijn Profiel</h1>
            <p>Beheer je accountgegevens</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🚗</div>
                <div class="stat-value">{{ $totalRentals ?? 0 }}</div>
                <div class="stat-label">Totaal Gereserveerd</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏰</div>
                <div class="stat-value">{{ $activeRentals ?? 0 }}</div>
                <div class="stat-label">Actieve Reserveringen</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value">€{{ number_format($totalSpent ?? 0, 0) }}</div>
                <div class="stat-label">Totaal Uitgegeven</div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="profile-info">
                    <h2>{{ auth()->user()->name }}</h2>
                    <p>Lid sinds {{ auth()->user()->created_at->format('F Y') }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h3>👤 Persoonlijke Gegevens</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Volledige Naam</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">E-mailadres</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefoonnummer</label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                value="{{ old('phone', auth()->user()->phone) }}" required>
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Adres</label>
                            <input type="text" id="address" name="address" class="form-control"
                                value="{{ old('address', auth()->user()->address) }}" required>
                            @error('address')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🔒 Wachtwoord Wijzigen</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="current_password">Huidig Wachtwoord</label>
                            <input type="password" id="current_password" name="current_password" class="form-control"
                                placeholder="Laat leeg om niet te wijzigen">
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password">Nieuw Wachtwoord</label>
                            <input type="password" id="new_password" name="new_password" class="form-control"
                                placeholder="Minimaal 8 tekens">
                            @error('new_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="new_password_confirmation">Bevestig Nieuw Wachtwoord</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="form-control" placeholder="Herhaal nieuw wachtwoord">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('home') }}" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-primary">💾 Opslaan</button>
                </div>

            </form>

        </div>
    </div>
    <!-- Account Verwijderen Sectie -->
    <div class="danger-zone">
        <div class="danger-card">


            <p class="danger-description">
                <strong>Let op:</strong> Als je je account verwijdert, worden al je persoonlijke gegevens en
                reserveringen <strong>permanent verwijderd</strong>. Deze actie kan niet ongedaan gemaakt worden.
            </p>

            <button type="button" onclick="openDeleteModal()" class="btn btn-danger">
                Account Verwijderen
            </button>
        </div>
    </div>
    </div>

    <!-- Modal voor Account Verwijderen -->
    <div id="deleteModal" class="modal-overlay hidden" onclick="closeDeleteModalOnOutsideClick(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3>Weet je het zeker?</h3>
            </div>

            <div class="modal-body">
                <p>
                    Deze actie kan <strong>niet ongedaan</strong> gemaakt worden. Al je gegevens en reserveringen
                    worden permanent verwijderd uit ons systeem.
                </p>

                <form method="POST" action="{{ route('user.profile.delete') }}" id="deleteForm">
                    @csrf
                    @method('DELETE')

                    <div class="form-group">
                        <label for="delete_password">Voer je wachtwoord in ter bevestiging:</label>
                        <input type="password" id="delete_password" name="password" class="form-control"
                            placeholder="Je wachtwoord" required autofocus>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>

            <div class="modal-actions">
                <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary">
                    Annuleren
                </button>

                <button type="submit" form="deleteForm" class="btn btn-danger">
                    Ja, verwijder mijn account
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('delete_password').focus();
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('delete_password').value = '';
        }

        function closeDeleteModalOnOutsideClick(event) {
            if (event.target.id === 'deleteModal') {
                closeDeleteModal();
            }
        }

        // Escape toets om modal te sluiten
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection
