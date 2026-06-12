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

    <div class="card">
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
                                    <div class="fw-semibold text-secondary">{{ $order->patient->name }}</div>
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
                                    <p class="mb-0">No se encontraron órdenes generadas en el sistema.</p>
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