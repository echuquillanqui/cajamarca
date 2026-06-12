@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 750px;">
    <div class="mb-3">
        <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Modificar Orden: {{ $order->codigo }}</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label for="patient_id" class="form-label fw-semibold">Paciente <span class="text-danger">*</span></label>
                        <select id="patient_id" name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $order->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label fw-semibold">Tipo de Destino <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="HISTORIA" {{ old('tipo', $order->tipo) == 'HISTORIA' ? 'selected' : '' }}>HISTORIA CLÍNICA</option>
                            <option value="HEMODIALISIS" {{ old('tipo', $order->tipo) == 'HEMODIALISIS' ? 'selected' : '' }}>HEMODIÁLISIS</option>
                            <option value="LABORATORIO" {{ old('tipo', $order->tipo) == 'LABORATORIO' ? 'selected' : '' }}>LABORATORIO</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="fecha" class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $order->fecha ? $order->fecha->format('Y-m-d') : '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="codigo" class="form-label fw-semibold">Código Único <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo', $order->codigo) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                            <option value="PENDIENTE" {{ old('estado', $order->estado) == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                            <option value="EN_PROCESO" {{ old('estado', $order->estado) == 'EN_PROCESO' ? 'selected' : '' }}>EN PROCESO</option>
                            <option value="FINALIZADA" {{ old('estado', $order->estado) == 'FINALIZADA' ? 'selected' : '' }}>FINALIZADA</option>
                            <option value="ANULADA" {{ old('estado', $order->estado) == 'ANULADA' ? 'selected' : '' }}>ANULADA</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" name="observaciones" rows="4">{{ old('observaciones', $order->observaciones) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-light rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color: var(--hc-primary); border: none;">
                        <i class="fa-solid fa-arrows-rotate me-2"></i>Actualizar Base
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#patient_id", { create: false });
    });
</script>
@endpush