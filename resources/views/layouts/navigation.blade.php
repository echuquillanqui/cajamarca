<nav class="navbar navbar-expand-md navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fa-solid fa-hand-holding-medical"></i>
            <span>{{ config('app.name', 'Hemodiálisis Control') }}</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto ms-3">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">
                            <i class="fa-solid fa-chart-pie me-1"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            <i class="fa-solid fa-users me-1"></i> Usuarios
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="pacientesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-injured me-1"></i> Pacientes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="pacientesDropdown">
                            <li><a class="dropdown-item" href="{{ route('patients.index') }}"><i class="fa-solid fa-list me-2 text-muted"></i> Registro</a></li>
                        </ul>
                    </li>

                    <!-- MENÚ DESPLEGABLE DE HEMODIÁLISIS ACTUALIZADO -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="sesionesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-kit-medical me-1"></i> Hemodiálisis
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="sesionesDropdown">
                            <span class="dropdown-header text-muted small fw-bold">Núcleo Operativo</span>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fa-solid fa-clipboard-list me-2 text-muted"></i> Órdenes Maestras</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            <span class="dropdown-header text-muted small fw-bold">Secciones Clínicas</span>
                            <li><a class="dropdown-item" href="{{ route('histories.index') }}"><i class="fa-solid fa-book-medical me-2 text-muted"></i> Historias Iniciales</a></li>
                            <li><a class="dropdown-item" href="{{ route('medicals.index') }}"><i class="fa-solid fa-user-doctor me-2 text-muted"></i> Prescripciones Médicas</a></li>
                            <li><a class="dropdown-item" href="{{ route('nurses.index') }}"><i class="fa-solid fa-user-nurse me-2 text-muted"></i> Notas de Enfermería (SOAPIE)</a></li>
                            <li><a class="dropdown-item" href="{{ route('treatments.index') }}"><i class="fa-solid fa-gauge-high me-2 text-muted"></i> Monitoreo Horario (Transdiálisis)</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            <span class="dropdown-header text-muted small fw-bold">Otros Destinos</span>
                            <li><a class="dropdown-item" href="{{ route('laboratories.index') }}"><i class="fa-solid fa-flask me-2 text-muted"></i> Laboratorio</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-print me-1"></i> Reportes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="reportesDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-excel text-success me-2"></i> Exportar Historias (Excel)</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fa-solid fa-file-pdf text-danger me-2"></i> Reportes Clínicos (PDF)</a></li>
                        </ul>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fa-solid fa-user-shield me-1"></i> {{ __('Ingresar') }}
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-light text-dark px-3 rounded-pill" href="{{ route('register') }}" style="font-weight: 600;">
                                {{ __('Registrarse') }}
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle user-dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fa-regular fa-circle-user me-1 text-info"></i> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                            <span class="dropdown-header text-muted small fw-bold">Gestión de Cuenta</span>
                            
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-arrow-right-from-bracket text-danger me-2"></i> {{ __('Cerrar Sesión') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>