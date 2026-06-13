@extends('layouts.app')

@section('content')
<div class="container px-4">
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-file-medical text-primary me-2"></i>Historias Clínicas de Nefrología
                </h4>
                <p class="text-muted small mb-0">Expedientes de ingreso a Hemodiálisis, monitoreo serológico y accesos vasculares.</p>
            </div>

        </div>
    </div>

    <form method="GET" action="{{ route('histories.index') }}" class="row mb-3 align-items-center g-2">
        <div class="col-md-5">
            <div class="input-group shadow-sm rounded-3">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Buscar por paciente, DNI, médico, orden o acceso...">
                @if($search !== '')
                    <a href="{{ route('histories.index') }}" class="btn btn-light border">Limpiar</a>
                @endif
            </div>
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-primary rounded-3 shadow-sm"><i class="fa-solid fa-filter me-2"></i>Filtrar</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light text-secondary small fw-bold text-uppercase">
                    <tr>
                        <th class="ps-4">Paciente</th>
                        <th>Fecha Ingreso</th>
                        <th>Acceso Vascular</th>
                        <th>Serología (VIH/HBsAg)</th>
                        <th>Médico Responsable</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $history->patient?->nombre ?? 'Paciente no disponible' }}</div>
                            <span class="text-muted small">ID Paciente: #{{ $history->patient_id }}</span>
                        </td>
                        <td>
                            <i class="fa-regular fa-calendar text-muted me-2"></i>{{ \Carbon\Carbon::parse($history->fecha_ingreso_hd)->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $history->tipo ?? 'NO ASIGNADO' }}</span>
                            <small class="text-muted d-block mt-1">{{ $history->localizacion }} - {{ $history->lado }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $history->hiv ? 'bg-danger' : 'bg-success bg-opacity-10 text-success' }} rounded-pill px-2">
                                VIH: {{ $history->hiv ? 'POS' : 'NEG' }}
                            </span>
                            <span class="badge {{ $history->hbsag ? 'bg-danger' : 'bg-success bg-opacity-10 text-success' }} rounded-pill px-2">
                                HepB: {{ $history->hbsag ? 'POS' : 'NEG' }}
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold text-secondary"><i class="fa-solid fa-user-md me-1 opacity-50"></i>{{ $history->user?->name ?? 'Usuario no disponible' }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('histories.show', $history->id) }}" class="btn btn-light border text-primary" title="Ver Historial Completo">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('histories.edit', $history->id) }}" class="btn btn-light border text-info" title="Editar Ficha">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button type="button" class="btn btn-light border text-danger" title="Eliminar Registro"
                                        onclick="confirmDelete(@js(route('histories.destroy', $history->id)), @js($history->patient?->nombre ?? 'Paciente no disponible'))">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fa-regular fa-folder-open fa-2x d-block mb-2"></i>
                            No se encontraron historias clínicas{{ $search !== '' ? ' para la búsqueda indicada.' : '.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $histories->links() }}
        </div>
    </div>
</div>

<form id="delete-history-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function confirmDelete(url, patientName) {
        Swal.fire({
            title: '¿Confirmar eliminación?',
            text: `El expediente clínico de "${patientName}" será removido de forma definitiva.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('delete-history-form');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush