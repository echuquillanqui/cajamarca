@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0f3057;">Evoluciones de Enfermería (SOAPIE)</h2>
            <p class="text-muted small mb-0">Registro y auditoría de notas metodológicas del personal de enfermería en sala.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <span class="fw-bold text-secondary"><i class="fa-solid fa-user-nurse text-primary me-2"></i>Notas de Enfermería Activas</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID Orden</th>
                            <th>Paciente</th>
                            <th>Datos Subjetivos (S)</th>
                            <th>Datos Objetivos (O)</th>
                            <th>UF Efectiva</th>
                            <th>Filtro</th>
                            <th>Enfermero(a)</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nurses as $nurse)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#{{ $nurse->order->codigo ?? $nurse->order_id }}</td>
                                <td><div class="fw-semibold text-dark">{{ $nurse->patient->nombre ?? 'N/A' }}</div></td>
                                <td><p class="mb-0 text-truncate" style="max-width: 180px;"><span class="badge bg-warning text-dark me-1">S</span>{{ $nurse->s_subjetivo }}</p></td>
                                <td><p class="mb-0 text-truncate" style="max-width: 180px;"><span class="badge bg-info text-white me-1">O</span>{{ $nurse->o_objetivo }}</p></td>
                                <td><span class="text-success fw-bold">{{ $nurse->uf_efectivo ?? '-' }}</span></td>
                                <td><small class="text-secondary">{{ $nurse->asp_filtro ?? '-' }}</small></td>
                                <td><small class="text-muted">{{ $nurse->user->name ?? 'Personal' }}</small></td>
                                <td class="text-end pe-4" x-data="{}">
                                    <div class="btn-group">
                                        <a href="{{ route('orders.show', $nurse->order_id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="{{ route('nurses.edit', $nurse->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                @click="
                                                    Swal.fire({
                                                        title: '¿Remover nota SOAPIE?',
                                                        text: 'Esta nota metodológica se eliminará del registro de enfermería.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#dc3545',
                                                        confirmButtonText: 'Sí, remover'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) { $refs['formDeleteN' + {{ $nurse->id }}].submit(); }
                                                    })
                                                ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                    <form x-ref="formDeleteN{{ $nurse->id }}" action="{{ route('nurses.destroy', $nurse->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-user-nurse fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">No se han registrado notas SOAPIE en el turno actual.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection