@extends('layouts.app')

@section('content')
<div class="container-fluid px-4" x-data="{ 
    tab: 'general',
    biopsia: {{ $history->biopsia_renal ? 'true' : 'false' }},
    antecedentes: {{ $history->antecedentes_personales ? $history->antecedentes_personales : '{ diabetes: false, hta: false, medicacion_previa: \'\' }' }},
    diagnosticos: {{ $history->diagnostico ? json_encode($history->diagnostico) : '[{ cie10: \'\', descripcion: \'\' }]' }},
    addDiag() { this.diagnosticos.push({ cie10: '', descripcion: '' }) },
    removeDiag(index) { this.diagnosticos.splice(index, 1) }
}">
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-pen-to-square text-info me-2"></i>Modificar Expediente Clínico #{{ $history->id }}</h4>
                <p class="text-muted small mb-0">Paciente: <span class="text-dark fw-bold">{{ $history->patient->name }}</span></p>
            </div>
            <a href="{{ route('histories.index') }}" class="btn btn-light border px-3 rounded-3 small text-secondary">
                Cancelar Cambios
            </a>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 overflow-x-auto pb-2">
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'general' ? 'btn-primary' : 'btn-light border'" @click="tab = 'general'">1. General</button>
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'anamnesis' ? 'btn-primary' : 'btn-light border'" @click="tab = 'anamnesis'">2. Anamnesis y Métricas</button>
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'vascular' ? 'btn-primary' : 'btn-light border'" @click="tab = 'vascular'">3. Acceso Vascular</button>
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'serologia' ? 'btn-primary' : 'btn-light border'" @click="tab = 'serologia'">4. Serología y Diagnósticos</button>
    </div>

    <form action="{{ route('histories.update', $history->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'general'">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Paciente Evaluado</label>
                        <select name="patient_id" class="form-select rounded-3 ts-edit" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $history->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Orden Médica Vinculada</label>
                        <select name="order_id" class="form-select rounded-3 ts-edit" required>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" {{ $history->order_id == $order->id ? 'selected' : '' }}>Orden #{{ $order->id }} - {{ $order->tipo_procedimiento }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Fecha Ingreso</label>
                        <input type="date" name="fecha_ingreso_hd" class="form-control rounded-3" value="{{ $history->fecha_ingreso_hd }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Servicio Origen</label>
                        <input type="text" name="serv_origen" class="form-control rounded-3" value="{{ $history->serv_origen }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Tiempo Enfermedad</label>
                        <input type="text" name="tiempo_enfermedad" class="form-control rounded-3" value="{{ $history->tiempo_enfermedad }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'anamnesis'">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-secondary">Relato Cronológico</label>
                        <textarea name="relato_cronologico" class="form-control rounded-3" rows="3">{{ $history->relato_cronologico }}</textarea>
                    </div>
                    
                    <div class="col-md-6 border-end pe-4">
                        <h6 class="fw-bold text-dark mb-3">Modificar Antecedentes Clínicos Personales (JSON Map)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="antecedentes.diabetes">
                            <label class="form-check-label small">Diabetes Mellitus Activa</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="antecedentes.hta">
                            <label class="form-check-label small">Hipertensión Arterial</label>
                        </div>
                        <div class="mt-2">
                            <label class="small text-muted mb-1">Medicación Previa Recibida</label>
                            <input type="text" x-model="antecedentes.medicacion_previa" class="form-control form-control-sm rounded-3">
                        </div>
                        <input type="hidden" name="antecedentes_personales" :value="JSON.stringify(antecedentes)">
                    </div>

                    <div class="col-md-6 ps-4">
                        <h6 class="fw-bold text-dark mb-2">Examen Físico Reciente</h6>
                        <div class="row g-2">
                            <div class="col-md-6"><label class="small text-muted">P.A. (mmHg)</label><input type="text" name="pa" class="form-control form-control-sm rounded-3" value="{{ $history->pa }}"></div>
                            <div class="col-md-6"><label class="small text-muted">F.C. (Lpm)</label><input type="number" name="fc" class="form-control form-control-sm rounded-3" value="{{ $history->fc }}"></div>
                            <div class="col-md-6"><label class="small text-muted">Peso Ingreso (Kg)</label><input type="number" step="0.01" name="peso_ingreso" class="form-control form-control-sm rounded-3" value="{{ $history->peso_ingreso }}"></div>
                            <div class="col-md-6"><label class="small text-muted">Sat O₂ (%)</label><input type="number" name="sat_o2" class="form-control form-control-sm rounded-3" value="{{ $history->sat_o2 }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'vascular'">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Tipo Acceso Vascular</label>
                        <select name="tipo" class="form-select rounded-3">
                            <option value="CVC TUNELIZADO" {{ $history->tipo == 'CVC TUNELIZADO' ? 'selected' : '' }}>CVC TUNELIZADO</option>
                            <option value="CVC TEMPORAL" {{ $history->tipo == 'CVC TEMPORAL' ? 'selected' : '' }}>CVC TEMPORAL</option>
                            <option value="FAV" {{ $history->tipo == 'FAV' ? 'selected' : '' }}>FAV</option>
                            <option value="INJERTO" {{ $history->tipo == 'INJERTO' ? 'selected' : '' }}>INJERTO</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Localización</label>
                        <select name="localizacion" class="form-select rounded-3">
                            <option value="RADIAL" {{ $history->localizacion == 'RADIAL' ? 'selected' : '' }}>RADIAL</option>
                            <option value="HUMERAL" {{ $history->localizacion == 'HUMERAL' ? 'selected' : '' }}>HUMERAL</option>
                            <option value="CERVICAL" {{ $history->localizacion == 'CERVICAL' ? 'selected' : '' }}>CERVICAL</option>
                            <option value="FEMORAL" {{ $history->localizacion == 'FEMORAL' ? 'selected' : '' }}>FEMORAL</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Lado Anatómico</label>
                        <select name="lado" class="form-select rounded-3">
                            <option value="DERECHA" {{ $history->lado == 'DERECHA' ? 'selected' : '' }}>DERECHA</option>
                            <option value="IZQUIERDA" {{ $history->lado == 'IZQUIERDA' ? 'selected' : '' }}>IZQUIERDA</option>
                        </select>
                    </div>
                    <div class="col-md-12"><hr></div>
                    <div class="col-md-3 pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="biopsia_renal" x-model="biopsia" value="1">
                            <label class="form-check-label small fw-bold text-dark">¿Posee Biopsia Renal?</label>
                        </div>
                    </div>
                    <div class="col-md-3" x-show="biopsia">
                        <label class="form-label small fw-bold text-secondary">Año Biopsia</label>
                        <input type="text" name="biopsia_renal_anio" class="form-control rounded-3" value="{{ $history->biopsia_renal_anio }}">
                    </div>
                    <div class="col-md-6" x-show="biopsia">
                        <label class="form-label small fw-bold text-secondary">Resultado Biopsia</label>
                        <input type="text" name="biopsia_renal_resultado" class="form-control rounded-3" value="{{ $history->biopsia_renal_resultado }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'serologia'">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <h6 class="fw-bold text-secondary small mb-2">Marcadores Serológicos Activos (Tamizaje)</h6>
                        <div class="d-flex flex-wrap gap-4 border p-3 rounded-3 bg-light">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="hiv" value="1" {{ $history->hiv ? 'checked' : '' }}><label class="form-check-label small">VIH (+)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="hbsag" value="1" {{ $history->hbsag ? 'checked' : '' }}><label class="form-check-label small">Ag Superficie Hep B (HBsAg)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="vhc" value="1" {{ $history->vhc ? 'checked' : '' }}><label class="form-check-label small">Hepatitis C (VHC)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="rpr" value="1" {{ $history->rpr ? 'checked' : '' }}><label class="form-check-label small">RPR / Sífilis Activa</label></div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0">Comorbilidades e Impresión Diagnóstica</h6>
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-2 px-2" @click="addDiag()">
                                <i class="fa-solid fa-plus-circle me-1"></i> Agregar Línea
                            </button>
                        </div>
                        <input type="hidden" name="diagnostico" :value="JSON.stringify(diagnosticos)">
                        <template x-for="(diag, idx) in diagnosticos" :key="idx">
                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-3"><input type="text" x-model="diag.cie10" class="form-control form-control-sm rounded-3" placeholder="CIE-10" required></div>
                                <div class="col-md-8"><input type="text" x-model="diag.descripcion" class="form-control form-control-sm rounded-3" placeholder="Descripción extendida" required></div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-sm btn-light text-danger border" @click="removeDiag(idx)" :disabled="diagnosticos.length === 1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="col-md-6 mt-4"><label class="form-label small fw-bold text-secondary">Consideraciones de Alta</label><textarea name="consideraciones_alta" class="form-control rounded-3" rows="2">{{ $history->consideraciones_alta }}</textarea></div>
                    <div class="col-md-6 mt-4"><label class="form-label small fw-bold text-secondary">Pendientes Próxima Sesión</label><textarea name="pendientes" class="form-control rounded-3" rows="2">{{ $history->pendientes }}</textarea></div>
                </div>
            </div>
            <div class="card-footer bg-light py-3 text-end rounded-bottom-4">
                <button type="submit" class="btn btn-info text-white px-5 rounded-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-square-check me-2"></i>Actualizar Ficha de Forma Definitiva
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.ts-edit').forEach(el => new TomSelect(el, { create: false }));
    });
</script>
@endpush