@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0f3057;">Monitoreo Horario de Tratamientos</h2>
            <p class="text-muted small mb-0">Panel de control de constantes vitales y presiones transmembrana (PTM) registradas transdiálisis.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <span class="fw-bold text-secondary"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Monitoreo de Parámetros Clínicos por Hora</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Orden</th>
                            <th>Paciente</th>
                            <th>Hora Reg.</th>
                            <th>P.A.</th>
                            <th>F.C.</th>
                            <th>SatO2</th>
                            <th>UF Hora</th>
                            <th>Bomba QB</th>
                            <th>PTM</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                            <tr>
                                <td class="ps-4 text-secondary fw-semibold">#{{ $treatment->order->codigo ?? $treatment->order_id }}</td>
                                <td><div class="fw-semibold text-dark">{{ $treatment->patient->nombre ?? 'N/A' }}</div></td>
                                <td class="fw-bold text-dark"><i class="fa-regular fa-clock text-info me-1"></i> {{ \Carbon\Carbon::parse($treatment->hora)->format('H:i') }}</td>
                                <td class="fw-semibold">{{ $treatment->pa ?? '-' }}</td>
                                <td>{{ $treatment->fc ? $treatment->fc . ' Rpm' : '-' }}</td>
                                <td>
                                    @if($treatment->sao2)
                                        <span class="badge bg-success-light text-success">{{ $treatment->sao2 }}%</span>
                                    @else - @endif
                                </td>
                                <td>{{ $treatment->uf_hora ? $treatment->uf_hora . ' ml/h' : '-' }}</td>
                                <td><span class="text-primary fw-semibold">{{ $treatment->qb ? $treatment->qb . ' ml' : '-' }}</span></td>
                                <td><span class="badge bg-secondary text-white">{{ $treatment->ptm }} mmHg</span></td>
                                <td class="text-end pe-4" x-data="{}">
                                    <div class="btn-group">
                                        <a href="{{ route('orders.show', $treatment->order_id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-eye"></i></a>
                                        <a href="{{ route('treatments.edit', $treatment->order_id) }}" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                @click="
                                                    Swal.fire({
                                                        title: '¿Remover esta hora de monitoreo?',
                                                        text: 'Esta fila de parámetros horarios será eliminada del reporte.',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#dc3545',
                                                        confirmButtonText: 'Sí, borrar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) { $refs['formDeleteT' + {{ $treatment->id }}].submit(); }
                                                    })
                                                ">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                    <form x-ref="formDeleteT{{ $treatment->id }}" action="{{ route('treatments.destroy', $treatment->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-gauge-high fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">No se registran monitoreos horarios en las diálisis del sistema.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(isset($treatments) && $treatments->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $treatments->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection