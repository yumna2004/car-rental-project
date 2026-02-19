@extends('layout')

@section('title', isset($vehicle) ? 'Voertuig Bewerken - AutoRental' : 'Voertuig Toevoegen - AutoRental')

@section('extra-css')
    <style>
        .form-container {
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

        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-control,
        select,
        textarea {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary-dark);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Outfit', sans-serif;
        }

        .form-control:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent-amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        select {
            cursor: pointer;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .help-text {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-gray);
        }

        .error-message {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--danger);
        }

        .image-preview {
            max-width: 200px;
            margin-top: 1rem;
            border-radius: 8px;
            border: 2px solid var(--border-color);
        }

        .images-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .images-preview-grid img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 1.5rem;
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
    </style>
@endsection

@section('content')
    <div class="form-container">
        <div class="page-header">
            <h1>{{ isset($vehicle) ? '✏️ Voertuig Bewerken' : '➕ Voertuig Toevoegen' }}</h1>
            <p>{{ isset($vehicle) ? 'Wijzig de gegevens van het voertuig' : 'Vul de onderstaande gegevens in om een nieuw voertuig toe te voegen' }}
            </p>
        </div>

        <div class="form-card">
            <form method="POST"
                action="{{ isset($vehicle) ? route('admin.vehicles.update', $vehicle->id) : route('admin.vehicles.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($vehicle))
                    @method('PUT')
                @endif

                <!-- Titel -->
                <div class="form-group">
                    <label for="title">Titel *</label>
                    <input type="text" id="title" name="title" class="form-control"
                        value="{{ old('title', $vehicle->title ?? '') }}" required placeholder="Bijv. Toyota Yaris 2024">
                    @error('title')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Beschrijving -->
                <div class="form-group">
                    <label for="description">Beschrijving *</label>
                    <textarea id="description" name="description" class="form-control" required placeholder="Beschrijf het voertuig...">{{ old('description', $vehicle->description ?? '') }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Categorie & Transmissie -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Categorie *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">-- Selecteer categorie --</option>
                            <option value="personenauto"
                                {{ old('category', $vehicle->category ?? '') == 'personenauto' ? 'selected' : '' }}>
                                Personenauto</option>
                            <option value="bestelbus"
                                {{ old('category', $vehicle->category ?? '') == 'bestelbus' ? 'selected' : '' }}>Bestelbus
                            </option>
                            <option value="verhuisbus"
                                {{ old('category', $vehicle->category ?? '') == 'verhuisbus' ? 'selected' : '' }}>Verhuisbus
                            </option>
                            <option value="aanhangwagen"
                                {{ old('category', $vehicle->category ?? '') == 'aanhangwagen' ? 'selected' : '' }}>
                                Aanhangwagen</option>
                        </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="transmission">Transmissie *</label>
                        <select id="transmission" name="transmission" class="form-control" required>
                            <option value="">-- Selecteer transmissie --</option>
                            <option value="automaat"
                                {{ old('transmission', $vehicle->transmission ?? '') == 'automaat' ? 'selected' : '' }}>
                                Automaat</option>
                            <option value="schakel"
                                {{ old('transmission', $vehicle->transmission ?? '') == 'schakel' ? 'selected' : '' }}>
                                Schakel</option>
                            <option value="n.v.t."
                                {{ old('transmission', $vehicle->transmission ?? '') == 'n.v.t.' ? 'selected' : '' }}>
                                N.v.t.</option>
                        </select>
                        @error('transmission')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Prijs & Regio -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="price_per_day">Prijs per dag (€) *</label>
                        <input type="number" id="price_per_day" name="price_per_day" class="form-control"
                            value="{{ old('price_per_day', $vehicle->price_per_day ?? '') }}" min="0" step="0.01"
                            required placeholder="45.00">
                        @error('price_per_day')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="region">Regio *</label>
                        <input type="text" id="region" name="region" class="form-control"
                            value="{{ old('region', $vehicle->region ?? '') }}" required
                            placeholder="Bijv. Amsterdam, Rotterdam">
                        @error('region')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Hoofdfoto -->
                <div class="form-group">
                    <label for="image">Hoofdfoto</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small class="help-text">💡 Ondersteunde formaten: JPG, PNG, GIF (max 2MB)</small>

                    @if (isset($vehicle) && $vehicle->image)
                        <img src="{{ asset('storage/' . $vehicle->image) }}" alt="Huidige hoofdfoto" class="image-preview">
                    @endif

                    @error('image')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Extra Foto's (NIEUW!) -->
                <div class="form-group">
                    <label for="images">Extra Foto's (Galerij)</label>
                    <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="help-text">
                        💡 Tip: Houd Ctrl (Windows) of Cmd (Mac) ingedrukt om meerdere foto's tegelijk te selecteren (max 5
                        aanbevolen)
                    </small>

                    @if (isset($vehicle) && $vehicle->images && count($vehicle->images) > 0)
                        <div class="images-preview-grid">
                            @foreach ($vehicle->images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Extra foto">
                            @endforeach
                        </div>
                    @endif

                    @error('images.*')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Beschikbaarheid -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="availability_start">Beschikbaar vanaf</label>
                        <input type="date" id="availability_start" name="availability_start" class="form-control"
                            value="{{ old('availability_start', isset($vehicle) && $vehicle->availability_start ? \Carbon\Carbon::parse($vehicle->availability_start)->format('Y-m-d') : '') }}">
                        <small class="help-text">Optioneel - laat leeg voor direct beschikbaar</small>
                        @error('availability_start')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="availability_end">Beschikbaar tot</label>
                        <input type="date" id="availability_end" name="availability_end" class="form-control"
                            value="{{ old('availability_end', isset($vehicle) && $vehicle->availability_end ? \Carbon\Carbon::parse($vehicle->availability_end)->format('Y-m-d') : '') }}">
                        <small class="help-text">Optioneel - laat leeg voor onbeperkt beschikbaar</small>
                        @error('availability_end')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        {{ isset($vehicle) ? '💾 Wijzigingen Opslaan' : '➕ Voertuig Toevoegen' }}
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        ❌ Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
