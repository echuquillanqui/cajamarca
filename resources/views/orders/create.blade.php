@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 750px;">
    <div class="mb-3">
        <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i> Regresar al panel
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-file-medical text-primary me-2"></i>Generar Nueva Orden</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label for="patient_id" class="form-label fw-semibold">Paciente <span class="text-danger">*</span></label>
                        <select id="patient_id" name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">Escriba para buscar paciente...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tipo" class="form-label fw-semibold">Tipo de Destino/Generación <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="HISTORIA" {{ old('tipo') == 'HISTORIA' ? 'selected' : '' }}>HISTORIA CLÍNICA</option>
                            <option value="HEMODIALISIS" {{ old('tipo') == 'HEMODIALISIS' ? 'selected' : '' }}>HEMODIÁLISIS</option>
                            <option value="LABORATORIO" {{ old('tipo') == 'LABORATORIO' ? 'selected' : '' }}>LABORATORIO</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="fecha" class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                        @error('fecha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="codigo" class="form-label fw-semibold">Código de Orden</label>
                        <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo') }}" placeholder="Dejar vacío para autogenerar">
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="estado" class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                        <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                            <option value="PENDIENTE" {{ old('estado') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                            <option value="EN_PROCESO" {{ old('estado') == 'EN_PROCESO' ? 'selected' : '' }}>EN PROCESO</option>
                            <option value="FINALIZADA" {{ old('estado') == 'FINALIZADA' ? 'selected' : '' }}>FINALIZADA</option>
                            <option value="ANULADA" {{ old('estado') == 'ANULADA' ? 'selected' : '' }}>ANULADA</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="observaciones" class="form-label fw-semibold">Observaciones Iniciales</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" name="observaciones" rows="4" placeholder="Indicaciones base para la orden..."></textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-light rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color: var(--hc-primary); border: none;">
                        <i class="fa-solid fa-save me-2"></i>Guardar y Proceder
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
        new TomSelect("#patient_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    });
</script>
@endpush