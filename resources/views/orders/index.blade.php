@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0f3057;">Módulo de Órdenes</h2>
            <p class="text-muted small mb-0">Gestión integrada de Historias, Hemodiálisis y Laboratorios.</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn btn-primary rounded-pill px-4" style="background-color: var(--hc-primary); border: none;">
            <i class="fa-solid fa-plus me-2"></i>Nueva Orden
        </a>
    </div>

    <!-- SECCIÓN DE FILTROS AGREGADA -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body bg-light">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="fecha_filtro" class="form-label small fw-bold text-secondary">Filtrar por Fecha</label>
                    <input type="date" name="fecha_filtro" id="fecha_filtro" class="form-control" 
                           value="{{ $request->get('fecha_filtro', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="paciente_nombre" class="form-label small fw-bold text-secondary">Nombres / Apellidos</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-user"></i></span>
                        <input type="text" name="paciente_nombre" id="paciente_nombre" class="form-control" 
                               placeholder="Buscar paciente..." value="{{ $request->get('paciente_nombre') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="paciente_dni" class="form-label small fw-bold text-secondary">Documento (DNI)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-id-card"></i></span>
                        <input type="text" name="paciente_dni" id="paciente_dni" class="form-control" 
                               placeholder="Número de DNI" value="{{ $request->get('paciente_dni') }}">
                    </div>
                </div>
                <div class="col-md-2 d-grid gap-2 d-md-flex text-end">
                    <button type="submit" class="btn btn-info text-white flex-grow-1"><i class="fa-solid fa-magnifying-glass me-1"></i> Buscar</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" title="Limpiar Filtros"><i class="fa-solid fa-eraser"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <span><i class="fa-solid fa-table-list me-2 text-info"></i>Todas las Órdenes</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Código</th>
                            <th>Paciente</th>
                            <th>Tipo de Orden</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Responsable</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $order->codigo }}</td>
                                <td>
                                    <!-- Cambiado $order->patient->name por $order->patient->nombre ya que se usa 'nombre' en el create -->
                                    <div class="fw-semibold text-secondary">{{ $order->patient->nombre }} {{ $order->patient->apellido ?? '' }}</div>
                                    @if($order->patient->dni)
                                        <small class="text-muted d-block">DNI: {{ $order->patient->dni }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($order->tipo)
                                        @case('HISTORIA')
                                            <span class="badge bg-secondary text-white px-2 py-1.5"><i class="fa-solid fa-book-medical me-1"></i> HISTORIA</span>
                                            @break
                                        @case('HEMODIALISIS')
                                            <span class="badge text-white px-2 py-1.5" style="background-color: var(--hc-secondary);"><i class="fa-solid fa-wave-square me-1"></i> HEMODIÁLISIS</span>
                                            @break
                                        @case('LABORATORIO')
                                            <span class="badge bg-purple text-white px-2 py-1.5" style="background-color: #6f42c1;"><i class="fa-solid fa-flask me-1"></i> LABORATORIO</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $order->fecha ? $order->fecha->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @switch($order->estado)
                                        @case('PENDIENTE') <span class="badge bg-warning text-dark rounded-pill">PENDIENTE</span> @break
                                        @case('EN_PROCESO') <span class="badge bg-info text-white rounded-pill">EN PROCESO</span> @break
                                        @case('FINALIZADA') <span class="badge bg-success rounded-pill">FINALIZADA</span> @break
                                        @case('ANULADA') <span class="badge bg-danger rounded-pill">ANULADA</span> @break
                                    @endswitch
                                </td>
                                <td>
                                    <small class="text-muted">{{ $order->user->name }}</small>
                                </td>
                                <td class="text-end pe-4" x-data="{}">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="Ver Flujo e Información">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                @click="
                                                    Swal.fire({
                                                        title: '¿Eliminar orden?',
                                                        text: 'Esto removerá el registro principal del sistema.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#dc3545',
                                                        cancelButtonColor: '#718096',
                                                        confirmButtonText: 'Sí, eliminar',
                                                        cancelButtonText: 'Cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $refs['formDelete' + {{ $order->id }}].submit();
                                                        }
                                                    })
                                                ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                    <form x-ref="formDelete{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-clipboard-list fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">No se encontraron órdenes generadas con los filtros seleccionados.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
            <div class="card-footer bg-white py-3">
                <!-- appends(request()->query()) asegura que al cambiar de página de la 1 a la 2 se mantengan tus filtros activos -->
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection