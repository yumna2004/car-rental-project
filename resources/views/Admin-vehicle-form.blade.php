@extends('layout')

@section('title', (isset($vehicle) ? 'Bewerk' : 'Nieuw') . ' Voertuig - AutoRental')

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
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            color: var(--accent-amber);
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
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

        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-amber), var(--accent-amber-light));
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .form-section h2 {
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

        .required {
            color: var(--danger);
            margin-left: 0.25rem;
        }

        .form-control,
        .form-select,
        .form-textarea {
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

        .form-control:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent-amber);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-select {
            cursor: pointer;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .form-help {
            color: var(--text-gray);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-input {
            display: none;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--primary-dark);
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-label:hover {
            border-color: var(--accent-amber);
            background: rgba(245, 158, 11, 0.05);
        }

        .file-upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .file-upload-text {
            color: var(--text-gray);
            text-align: center;
        }

        .file-upload-text strong {
            color: var(--accent-amber);
        }

        .image-preview {
            margin-top: 1rem;
            border-radius: 8px;
            overflow: hidden;
            max-width: 300px;
        }

        .image-preview img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-submit {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 2rem;
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
    </style>
@endsection

@section('content')
    <div class="form-container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <span>/</span>
            <span>{{ isset($vehicle) ? 'Bewerk Voertuig' : 'Nieuw Voertuig' }}</span>
        </nav>

        <div class="page-header">
            <h1>{{ isset($vehicle) ? '✏️ Bewerk Voertuig' : '➕ Nieuw Voertuig' }}</h1>
        </div>

        <div class="form-card">
            <form method="POST"
                action="{{ isset($vehicle) ? route('admin.vehicles.update', $vehicle->id) : route('admin.vehicles.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($vehicle))
                    @method('PUT')
                @endif

                <!-- Basic Information -->
                <div class="form-section">
                    <h2>📝 Basis Informatie</h2>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="title">
                                Voertuig Titel
                                <span class="required">*</span>
                            </label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="{{ old('title', $vehicle->title ?? '') }}" placeholder="bijv. BMW 3 Serie 2023"
                                required>
                            @error('title')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">
                                Categorie
                                <span class="required">*</span>
                            </label>
                            <select id="category" name="category" class="form-select" required>
                                <option value="">Selecteer categorie</option>
                                <option value="personenauto"
                                    {{ old('category', $vehicle->category ?? '') == 'personenauto' ? 'selected' : '' }}>
                                    Personenauto</option>
                                <option value="bestelbus"
                                    {{ old('category', $vehicle->category ?? '') == 'bestelbus' ? 'selected' : '' }}>
                                    Bestelbus</option>
                                <option value="verhuisbus"
                                    {{ old('category', $vehicle->category ?? '') == 'verhuisbus' ? 'selected' : '' }}>
                                    Verhuisbus</option>
                                <option value="aanhangwagen"
                                    {{ old('category', $vehicle->category ?? '') == 'aanhangwagen' ? 'selected' : '' }}>
                                    Aanhangwagen</option>
                            </select>
                            @error('category')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transmission">
                                Transmissie
                                <span class="required">*</span>
                            </label>
                            <select id="transmission" name="transmission" class="form-select" required>
                                <option value="">Selecteer transmissie</option>
                                <option value="automaat"
                                    {{ old('transmission', $vehicle->transmission ?? '') == 'automaat' ? 'selected' : '' }}>
                                    Automaat</option>
                                <option value="schakel"
                                    {{ old('transmission', $vehicle->transmission ?? '') == 'schakel' ? 'selected' : '' }}>
                                    Schakel</option>
                            </select>
                            @error('transmission')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="description">
                                Beschrijving
                                <span class="required">*</span>
                            </label>
                            <textarea id="description" name="description" class="form-textarea"
                                placeholder="Uitgebreide beschrijving van het voertuig..." required>{{ old('description', $vehicle->description ?? '') }}</textarea>
                            @error('description')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location & Pricing -->
                <div class="form-section">
                    <h2>📍 Locatie & Prijzen</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="region">
                                Regio/Stad
                                <span class="required">*</span>
                            </label>
                            <select id="region" name="region" class="form-select" required>
                                <option value="">Selecteer regio</option>
                                <option value="Amsterdam"
                                    {{ old('region', $vehicle->region ?? '') == 'Amsterdam' ? 'selected' : '' }}>Amsterdam
                                </option>
                                <option value="Rotterdam"
                                    {{ old('region', $vehicle->region ?? '') == 'Rotterdam' ? 'selected' : '' }}>Rotterdam
                                </option>
                                <option value="Utrecht"
                                    {{ old('region', $vehicle->region ?? '') == 'Utrecht' ? 'selected' : '' }}>Utrecht
                                </option>
                                <option value="Den Haag"
                                    {{ old('region', $vehicle->region ?? '') == 'Den Haag' ? 'selected' : '' }}>Den Haag
                                </option>
                                <option value="Eindhoven"
                                    {{ old('region', $vehicle->region ?? '') == 'Eindhoven' ? 'selected' : '' }}>Eindhoven
                                </option>
                                <option value="Groningen"
                                    {{ old('region', $vehicle->region ?? '') == 'Groningen' ? 'selected' : '' }}>Groningen
                                </option>
                                <option value="Maastricht"
                                    {{ old('region', $vehicle->region ?? '') == 'Maastricht' ? 'selected' : '' }}>
                                    Maastricht</option>
                            </select>
                            @error('region')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price_per_day">
                                Prijs per Dag (€)
                                <span class="required">*</span>
                            </label>
                            <input type="number" id="price_per_day" name="price_per_day" class="form-control"
                                value="{{ old('price_per_day', $vehicle->price_per_day ?? '') }}" placeholder="50.00"
                                step="0.01" min="0" required>
                            @error('price_per_day')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="form-section">
                    <h2>📷 Afbeelding</h2>
                    <div class="form-group">
                        <div class="file-upload-wrapper">
                            <input type="file" id="image" name="image" class="file-upload-input" accept="image/*"
                                onchange="previewImage(event)">
                            <label for="image" class="file-upload-label">
                                <div class="file-upload-icon">📸</div>
                                <div class="file-upload-text">
                                    <strong>Klik om een afbeelding te selecteren</strong>
                                    <br>
                                    <small>of sleep hier naartoe</small>
                                </div>
                            </label>
                            @error('image')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="imagePreview" class="image-preview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                        </div>

                        @if (isset($vehicle) && $vehicle->image)
                            <div class="image-preview">
                                <p style="color: var(--text-gray); margin-bottom: 0.5rem; font-size: 0.9rem;">Huidige
                                    afbeelding:</p>
                                <img src="{{ asset('storage/' . $vehicle->image) }}" alt="{{ $vehicle->title }}">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Availability -->
                <div class="form-section">
                    <h2>📅 Beschikbaarheid</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="availability_start">Beschikbaar vanaf</label>
                            <input type="date" id="availability_start" name="availability_start" class="form-control"
                                value="{{ old('availability_start', isset($vehicle) ? $vehicle->availability_start : date('Y-m-d')) }}">
                            <span class="form-help">Laat leeg voor direct beschikbaar</span>
                        </div>

                        <div class="form-group">
                            <label for="availability_end">Beschikbaar tot</label>
                            <input type="date" id="availability_end" name="availability_end" class="form-control"
                                value="{{ old('availability_end', $vehicle->availability_end ?? '') }}">
                            <span class="form-help">Laat leeg voor onbeperkt</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-primary btn-submit">
                        {{ isset($vehicle) ? '💾 Wijzigingen Opslaan' : '✓ Voertuig Toevoegen' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Drag and drop functionality
        const fileUploadLabel = document.querySelector('.file-upload-label');
        const fileUploadInput = document.getElementById('image');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadLabel.addEventListener(eventName, () => {
                fileUploadLabel.style.borderColor = 'var(--accent-amber)';
                fileUploadLabel.style.background = 'rgba(245, 158, 11, 0.1)';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadLabel.addEventListener(eventName, () => {
                fileUploadLabel.style.borderColor = '';
                fileUploadLabel.style.background = '';
            });
        });

        fileUploadLabel.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            fileUploadInput.files = files;

            if (files.length > 0) {
                previewImage({
                    target: {
                        files: files
                    }
                });
            }
        });
    </script>
@endsection
