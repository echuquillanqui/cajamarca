@extends('layouts.app')

@php
    $oldAntecedentes = old('antecedentes_personales');
    $antecedentesState = is_string($oldAntecedentes) ? json_decode($oldAntecedentes, true) : $oldAntecedentes;
    $antecedentesState = is_array($antecedentesState) ? $antecedentesState : ['diabetes' => false, 'hta' => false, 'medicacion_previa' => ''];

    $oldDiagnosticos = old('diagnostico');
    $diagnosticosState = is_string($oldDiagnosticos) ? json_decode($oldDiagnosticos, true) : $oldDiagnosticos;
    $diagnosticosState = is_array($diagnosticosState) && count($diagnosticosState) ? $diagnosticosState : [['cie10' => '', 'descripcion' => '']];
@endphp

@section('content')
<div class="container-fluid px-4" x-data="{ 
    tab: 'general',
    biopsia: {{ old('biopsia_renal') ? 'true' : 'false' }},
    antecedentes: @js($antecedentesState),
    diagnosticos: @js($diagnosticosState),
    addDiag() { this.diagnosticos.push({ cie10: '', descripcion: '' }) },
    removeDiag(index) { this.diagnosticos.splice(index, 1) }
}">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-medical text-primary me-2"></i>Nueva Ficha Clínico-Anamnésica</h4>
                <p class="text-muted small mb-0">Apertura formal de expediente para tratamientos de depuración extracorpórea.</p>
            </div>
            <a href="{{ route('histories.index') }}" class="btn btn-light border px-3 rounded-3 small text-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Volver al Listado
            </a>
        </div>
    </div>

    <form action="{{ route('histories.store') }}" method="POST" autocomplete="off">
        @csrf
        @include('histories._form')
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.js-history-select').forEach(el => new TomSelect(el, { create: false }));
    });
</script>
@endpush
