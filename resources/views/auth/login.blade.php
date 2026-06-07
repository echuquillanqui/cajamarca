@extends('layouts.app')

@section('content')
<div class="container py-4" style="margin-top: -2.5rem; min-height: calc(100vh - 120px); display: flex; align-items: center;">
    <div class="row g-0 w-100" style="min-height: 75vh; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 20px; overflow: hidden; background: #ffffff;">

        <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between p-5 text-white"
             style="background: linear-gradient(135deg, #0f3057 0%, #164e63 100%); position: relative;">

            <div style="position: absolute; right: -50px; bottom: -50px; font-size: 25rem; color: rgba(255,255,255,0.03); pointer-events: none;">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>

            <div>
                <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold mb-3">Sistema Clínico</span>
                <h1 class="display-5 fw-bold" style="letter-spacing: -1px;">Bienvenido de nuevo</h1>
                <p class="lead opacity-75">Portal de Gestión e Historial de Pacientes en Hemodiálisis.</p>
            </div>

            <div class="my-auto py-4">
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fa-solid fa-shield-medical fa-xl text-info"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Entorno 100% Seguro</h5>
                        <p class="small opacity-75 mb-0">Cumplimos con los estándares de protección de datos médicos cifrados.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fa-solid fa-clock-rotate-left fa-xl text-info"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Monitoreo en Tiempo Real</h5>
                        <p class="small opacity-75 mb-0">Acceso inmediato a programaciones, reportes de laboratorio y filtrados.</p>
                    </div>
                </div>
            </div>

            <div class="border-top border-white border-opacity-10 pt-3">
                <small class="opacity-50">Por favor, mantenga sus credenciales a resguardo de terceros.</small>
            </div>
        </div>

        <div class="col-lg-6 d-flex align-items-center p-4 p-md-5 bg-white">
            <div class="w-100 mx-auto" style="max-width: 420px;">

                <div class="mb-4">
                    <div class="d-lg-none text-center mb-3">
                        <span class="p-3 bg-info bg-opacity-10 rounded-circle d-inline-block text-cyan">
                            <i class="fa-solid fa-hand-holding-medical fa-2xl" style="color: #164e63;"></i>
                        </span>
                    </div>
                    <h2 class="fw-bold text-dark mb-1">{{ __('Iniciar Sesión') }}</h2>
                    <p class="text-muted small">Ingrese sus datos de acceso para ingresar a la plataforma médica.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label small fw-semibold text-secondary">{{ __('Usuario o Correo Electrónico') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input id="login" type="text"
                                   class="form-control bg-light border-start-0 @error('login') is-invalid @enderror"
                                   name="login" value="{{ old('login') }}" required autocomplete="username" autofocus
                                   placeholder="usuario o ejemplo@clinica.com"
                                   style="border-radius: 0 8px 8px 0; padding: 0.6rem 0.75rem;">

                            @error('login')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label small fw-semibold text-secondary mb-0">{{ __('Contraseña') }}</label>
                            @if (Route::has('password.request'))
                                <a class="small text-decoration-none text-info fw-medium" href="{{ route('password.request') }}">
                                    {{ __('¿Olvidó su contraseña?') }}
                                </a>
                            @endif
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input id="password" type="password"
                                   class="form-control bg-light border-start-0 @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password"
                                   placeholder="••••••••"
                                   style="border-radius: 0 8px 8px 0; padding: 0.6rem 0.75rem;">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted user-select-none" for="remember">
                                {{ __('Mantener sesión activa') }}
                            </label>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn text-white fw-bold py-2.5 shadow-sm"
                                style="background: #164e63; border: none; border-radius: 8px; transition: background 0.2s;">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> {{ __('Acceder al Panel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    /* Pequeños ajustes locales que potencian la experiencia de login */
    .input-group .form-control:focus {
        background-color: #ffffff !important;
        border-color: #164e63 !important;
        box-shadow: none !important;
    }
    .input-group .form-control:focus + .input-group-text,
    .input-group:focus-within .input-group-text {
        border-color: #164e63 !important;
        background-color: #ffffff !important;
        color: #164e63 !important;
    }
    .btn-primary:hover {
        background: #0f3057 !important;
    }
</style>
@endsection
