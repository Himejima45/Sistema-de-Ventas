@extends('layouts.theme.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row justify-content-center mb-3" style="max-width: 100%">
                    <img src="{{ asset('assets/img/Logo_no_bg.png') }}"
                        style="height: 22rem; width: 16rem; object-fit: contain" alt="Logo de la empresa">
                </div>
                <div class="card">
                    <div class="card-header">{{ __('Registrarme') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre') }} *</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required autofocus placeholder="Ej: José"
                                        maxlength="255" pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                                        title="Solo letras y espacios (máximo 255 caracteres)">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Apellido') }} *</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text"
                                        class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                        value="{{ old('last_name') }}" required autocomplete="last_name"
                                        placeholder="Ej: Gómez" autofocus maxlength="255"
                                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                                        title="Solo letras y espacios (máximo 255 caracteres)">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('CI') }} *</label>

                                <div class="col-md-6">
                                    <input id="document" type="text"
                                        class="form-control @error('document') is-invalid @enderror" name="document"
                                        value="{{ old('document') }}" required autocomplete="document"
                                        placeholder="Ej: 12512102" autofocus minlength="6" maxlength="11"
                                        pattern="^\d{6,11}$" title="Entre 6 y 11 dígitos numéricos">

                                    @error('document')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Correo electrónico') }} *</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="Ej: micorreo@email.com"
                                        required autocomplete="email" maxlength="255"
                                        pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                        title="Ingrese un correo electrónico válido">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Teléfono') }}
                                    *</label>

                                <div class="col-md-6">
                                    <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone') }}" placeholder="Ej: 04125784159" required
                                        autocomplete="phone" minlength="11" maxlength="11" pattern="^\d{11}$"
                                        title="Exactamente 11 dígitos numéricos">

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address" class="col-md-4 col-form-label text-md-end">{{ __('Dirección') }}
                                    *</label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('address') is-invalid @enderror" name="address"
                                        value="{{ old('address') }}" placeholder="Ej: El consejo" required
                                        autocomplete="address" minlength="3" maxlength="50"
                                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s.,#-]+$"
                                        title="Entre 3 y 50 caracteres (letras, números, espacios y .,#-)">

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}
                                    *</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required placeholder="Ej: ***********" autocomplete="new-password" minlength="8"
                                        pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$"
                                        title="Mínimo 8 caracteres, al menos una letra y un número">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirmar contraseña') }} *</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        placeholder="Ej: ***********" name="password_confirmation" required>

                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Registrarme') }}
                                    </button>
                                    <a href="{{ route('login') }}" class="btn btn-link">
                                        Ya estoy registrado
                                    </a>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 offset-md-4">
                                    <small class="text-muted">* Campos obligatorios</small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection