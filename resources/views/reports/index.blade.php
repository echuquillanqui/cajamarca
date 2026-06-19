@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0f3057;">Módulo de Reportes</h2>
            <p class="text-muted small mb-0">Consulta, filtra y exporta la información clínica desde un solo lugar.</p>
        </div>
        <a href="{{ route('reports.export.excel', request()->query()) }}" class="btn btn-success rounded-pill px-4">
            <i class="fa-solid fa-file-excel me-2"></i>Exportar Excel
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <span class="fw-semibold"><i class="fa-solid fa-filter me-2 text-info"></i>Filtros del reporte</span>
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="fecha_filtro" class="form-label small fw-bold text-secondary">Fecha</label>
                    <input type="date" name="fecha_filtro" id="fecha_filtro" class="form-control" value="{{ $filters['fecha_filtro'] }}">
                </div>
                <div class="col-md-3">
                    <label for="paciente_nombre" class="form-label small fw-bold text-secondary">Paciente</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="paciente_nombre" id="paciente_nombre" class="form-control" placeholder="Buscar paciente..." value="{{ $filters['paciente_nombre'] }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="paciente_dni" class="form-label small fw-bold text-secondary">Documento (DNI)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-id-card"></i></span>
                        <input type="text" name="paciente_dni" id="paciente_dni" class="form-control" placeholder="Número de DNI" value="{{ $filters['paciente_dni'] }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="tipo" class="form-label small fw-bold text-secondary">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="HISTORIA" @selected($filters['tipo'] === 'HISTORIA')>Historia</option>
                        <option value="HEMODIALISIS" @selected($filters['tipo'] === 'HEMODIALISIS')>Hemodiálisis</option>
                        <option value="LABORATORIO" @selected($filters['tipo'] === 'LABORATORIO')>Laboratorio</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label small fw-bold text-secondary">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="PENDIENTE" @selected($filters['estado'] === 'PENDIENTE')>Pendiente</option>
                        <option value="EN_PROCESO" @selected($filters['estado'] === 'EN_PROCESO')>En proceso</option>
                        <option value="FINALIZADA" @selected($filters['estado'] === 'FINALIZADA')>Finalizada</option>
                        <option value="ANULADA" @selected($filters['estado'] === 'ANULADA')>Anulada</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid gap-2 d-md-flex text-end">
                    <button type="submit" class="btn btn-info text-white flex-grow-1" title="Buscar"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros"><i class="fa-solid fa-eraser"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <span><i class="fa-solid fa-table-list me-2 text-info"></i>Resultados del Reporte</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Paciente</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Registros</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $order->codigo }}</td>
                                <td>
                                    <div class="fw-semibold text-secondary">{{ $order->patient->nombre }}</div>
                                    @if($order->patient->dni)
                                        <small class="text-muted d-block">DNI: {{ $order->patient->dni }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $order->tipo }}</span></td>
                                <td>{{ $order->fecha ? $order->fecha->format('d/m/Y') : '-' }}</td>
                                <td><span class="badge bg-info text-white rounded-pill">{{ str_replace('_', ' ', $order->estado) }}</span></td>
                                <td>
                                    <small class="text-muted d-block">Historias: {{ $order->histories->count() }} | Médicas: {{ $order->medicals->count() }}</small>
                                    <small class="text-muted d-block">Enfermería: {{ $order->nurses->count() }} | Monitoreos: {{ $order->treatments->count() }} | Lab: {{ $order->laboratories->count() }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" title="Ver detalle"><i class="fa-solid fa-eye"></i></a>
                                        @if($order->tipo === 'HEMODIALISIS')
                                            <a href="{{ route('orders.hemodialysis.pdf', $order) }}" class="btn btn-sm btn-outline-danger" title="PDF clínico"><i class="fa-solid fa-file-pdf"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-print fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">No se encontraron registros para los filtros seleccionados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
