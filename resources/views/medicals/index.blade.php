@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0f3057;">Listado de Órdenes Médicas</h2>
            <p class="text-muted small mb-0">Gestión de Registros Médicos de Hemodiálisis.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span><i class="fa-solid fa-stethoscope me-2 text-info"></i>Órdenes Médicas</span>
        </div>
        <div class="card-body">
            <form action="{{ route('medicals.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por paciente, nro de orden, o sesión..." class="form-control">
                    <button type="submit" class="btn btn-primary" style="background-color: var(--hc-primary); border: none;">
                        <i class="fa-solid fa-magnifying-glass me-1"></i>Buscar
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Orden</th>
                            <th>Paciente</th>
                            <th>Fecha Sesión</th>
                            <th>Médico</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicals as $medical)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                {{ $medical->order?->codigo ?? '-' }}
                            </td>
                            <td>
                                <div class="fw-semibold text-secondary">{{ $medical->patient?->nombre ?? '-' }}</div>
                            </td>
                            <td>
                                {{ $medical->fecha_sesion?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td>
                                <small class="text-muted">{{ $medical->user?->name ?? '-' }}</small>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('medicals.edit', $medical->id) }}" class="btn btn-sm btn-outline-primary" title="Registro Médico">
                                    <i class="fa-solid fa-file-medical"></i> Registro Médico
                                </a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-stethoscope fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">No se encontraron órdenes médicas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(isset($medicals) && $medicals->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $medicals->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection