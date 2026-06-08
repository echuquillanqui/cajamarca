@extends('layouts.app')

@section('content')
<div class="container-fluid px-4" x-data="{ 
    tab: 'general',
    biopsia: false,
    antecedentes: { diabetes: false, hta: false, medicacion_previa: '' },
    diagnosticos: [{ cie10: '', descripcion: '' }],
    addDiag() { this.diagnosticos.push({ cie10: '', descripcion: '' }) },
    removeDiag(index) { this.diagnosticos.splice(index, 1) }
}">
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-file-medical text-primary me-2"></i>Nueva Ficha Clínico-Anamnésica</h4>
                <p class="text-muted small mb-0">Apertura formal de expediente para tratamientos de depuración extracorpórea.</p>
            </div>
            <a href="{{ route('histories.index') }}" class="btn btn-light border px-3 rounded-3 small text-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>Volver al Listado
            </a>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 overflow-x-auto pb-2">
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'general' ? 'btn-primary' : 'btn-light border'" @click="tab = 'general'">1. General e Ingreso</button>
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'anamnesis' ? 'btn-primary' : 'btn-light border'" @click="tab = 'anamnesis'">2. Anamnesis y Sistemas</button>
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'vascular' ? 'btn-primary' : 'btn-light border'" @click="tab = 'vascular'">3. Acceso Vascular</button>
        <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'serologia' ? 'btn-primary' : 'btn-light border'" @click="tab = 'serologia'">4. Serología y Diagnósticos</button>
    </div>

    <form action="{{ route('histories.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'general'">
            <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-hospital-user me-2 text-primary"></i>Datos de Admisión de Especialidad</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Paciente Evaluado</label>
                        <select name="patient_id" class="form-select rounded-3 id-select-ts" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Orden Médica Vinculada</label>
                        <select name="order_id" class="form-select rounded-3 order-select-ts" required>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}">Orden #{{ $order->id }}{{ $order->codigo ? ' - '.$order->codigo : '' }}{{ $order->fecha ? ' - '.$order->fecha->format('d/m/Y') : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Fecha de Ingreso a HD</label>
                        <input type="date" name="fecha_ingreso_hd" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Servicio de Origen</label>
                        <input type="text" name="serv_origen" class="form-control rounded-3" placeholder="Ej: Emergencia, UCI">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Tiempo Enfermedad</label>
                        <input type="text" name="tiempo_enfermedad" class="form-control rounded-3" placeholder="Ej: 3 meses, 5 años">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Forma de Inicio</label>
                        <select name="inicio_enfermedad" class="form-select rounded-3">
                            <option value="INSIDIOSO">INSIDIOSO</option>
                            <option value="SÚBITO">SÚBITO</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-secondary">Curso evolutivo</label>
                        <select name="curso_enfermedad" class="form-select rounded-3">
                            <option value="PROGRESIVO">PROGRESIVO</option>
                            <option value="ESTACIONARIO">ESTACIONARIO</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'anamnesis'">
            <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-notes-medical me-2 text-primary"></i>Evaluación de Aparatos, Sistemas y Antecedentes</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label small fw-bold text-secondary">Relato Cronológico (Evolución Clínica)</label>
                        <textarea name="relato_cronologico" class="form-control rounded-3" rows="3"></textarea>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary">Apetito</label>
                        <input type="text" name="apetito" class="form-control rounded-3" value="CONSERVADO">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary">Sed</label>
                        <input type="text" name="sed" class="form-control rounded-3" value="AUMENTADO">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary">Heces</label>
                        <input type="text" name="heces" class="form-control rounded-3" value="NORMAL">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary">Sueño</label>
                        <input type="text" name="sueno" class="form-control rounded-3" value="CONSERVADO">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Diuresis de Ingreso</label>
                        <input type="text" name="diuresis_ingreso" class="form-control rounded-3" placeholder="Ej: Oliguria, Anuria">
                    </div>

                    <div class="col-md-6 border-end pe-4">
                        <h6 class="fw-bold text-dark mb-3">Antecedentes Clínicos Personales</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="antecedentes.diabetes">
                            <label class="form-check-label small">Diabetes Mellitus (Tipo I/II)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="antecedentes.hta">
                            <label class="form-check-label small">Hipertensión Arterial Sistémica</label>
                        </div>
                        <div class="mt-2">
                            <label class="small text-muted mb-1">Medicación Previa Recibida</label>
                            <input type="text" x-model="antecedentes.medicacion_previa" class="form-control form-control-sm rounded-3" placeholder="Ej: Losartán 50mg, Furosemida">
                        </div>
                        <input type="hidden" name="antecedentes_personales" :value="JSON.stringify(antecedentes)">
                    </div>

                    <div class="col-md-6 ps-4">
                        <h6 class="fw-bold text-dark mb-2">Examen Físico Funcional (Funciones Vitales)</h6>
                        <div class="row g-2">
                            <div class="col-md-4"><label class="small text-muted">P.A. (mmHg)</label><input type="text" name="pa" class="form-control form-control-sm rounded-3" placeholder="120/80"></div>
                            <div class="col-md-4"><label class="small text-muted">F.C. (Lpm)</label><input type="number" name="fc" class="form-control form-control-sm rounded-3" placeholder="75"></div>
                            <div class="col-md-4"><label class="small text-muted">F.R. (Rpm)</label><input type="number" name="fr" class="form-control form-control-sm rounded-3" placeholder="18"></div>
                            <div class="col-md-4"><label class="small text-muted">Sat O₂ (%)</label><input type="number" name="sat_o2" class="form-control form-control-sm rounded-3" placeholder="98"></div>
                            <div class="col-md-4"><label class="small text-muted">Peso (Kg)</label><input type="number" step="0.01" name="peso_ingreso" class="form-control form-control-sm rounded-3"></div>
                            <div class="col-md-4"><label class="small text-muted">Talla (m)</label><input type="number" step="0.01" name="talla_ingreso" class="form-control form-control-sm rounded-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'vascular'">
            <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-code-fork me-2 text-primary"></i>Especificación del Acceso Vascular Activo</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Tipo de Acceso Primario</label>
                        <select name="tipo" class="form-select rounded-3">
                            <option value="">-- SELECCIONE --</option>
                            <option value="CVC TUNELIZADO">CVC TUNELIZADO</option>
                            <option value="CVC TEMPORAL">CVC TEMPORAL</option>
                            <option value="FAV">FAV (Fístula)</option>
                            <option value="INJERTO">INJERTO</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Localización Anatómica</label>
                        <select name="localizacion" class="form-select rounded-3">
                            <option value="RADIAL">RADIAL</option>
                            <option value="HUMERAL">HUMERAL</option>
                            <option value="CERVICAL">CERVICAL</option>
                            <option value="FEMORAL">FEMORAL</option>
                            <option value="OTROS">OTROS</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-secondary">Lado Corpóreo</label>
                        <select name="lado" class="form-select rounded-3">
                            <option value="DERECHA">DERECHA</option>
                            <option value="IZQUIERDA">IZQUIERDA</option>
                        </select>
                    </div>
                    
                    <div class="col-md-12"><hr class="my-2"></div>

                    <div class="col-md-3 pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="biopsia_renal" x-model="biopsia" value="1">
                            <label class="form-check-label small fw-bold text-dark">¿Posee Biopsia Renal?</label>
                        </div>
                    </div>
                    <div class="col-md-3" x-show="biopsia">
                        <label class="form-label small fw-bold text-secondary">Año Biopsia</label>
                        <input type="text" name="biopsia_renal_anio" class="form-control rounded-3" maxlength="4" placeholder="Ej: 2024">
                    </div>
                    <div class="col-md-6" x-show="biopsia">
                        <label class="form-label small fw-bold text-secondary">Resultado Diagnóstico Biopsia</label>
                        <input type="text" name="biopsia_renal_resultado" class="form-control rounded-3" placeholder="Ej: Nefropatía IgA">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" x-show="tab === 'serologia'">
            <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-virus-covid me-2 text-primary"></i>Serología e Impresiones Diagnósticas Estructuradas</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-12">
                        <h6 class="fw-bold text-secondary small mb-2">Marcadores Serológicos Activos (Tamizaje)</h6>
                        <div class="d-flex flex-wrap gap-4 border p-3 rounded-3 bg-light">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="hiv" value="1"><label class="form-check-label small">VIH (+)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="hbsag" value="1"><label class="form-check-label small">Ag Superficie Hep B (HBsAg)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="vhc" value="1"><label class="form-check-label small">Hepatitis C (VHC)</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="rpr" value="1"><label class="form-check-label small">RPR / Sífilis Activa</label></div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0">Comorbilidades e Impresión Diagnóstica Relacionada</h6>
                            <button type="button" class="btn btn-xs btn-outline-primary rounded-2 px-2" @click="addDiag()">
                                <i class="fa-solid fa-plus-circle me-1"></i> Agregar Línea
                            </button>
                        </div>
                        
                        <input type="hidden" name="diagnostico" :value="JSON.stringify(diagnosticos)">
                        
                        <template x-for="(diag, idx) in diagnosticos" :key="idx">
                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-3">
                                    <input type="text" x-model="diag.cie10" class="form-control form-control-sm rounded-3" placeholder="Código CIE-10 (Ej: N18.5)" required>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" x-model="diag.descripcion" class="form-control form-control-sm rounded-3" placeholder="Descripción extendida del diagnóstico clínico" required>
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-sm btn-light text-danger border" @click="removeDiag(idx)" :disabled="diagnosticos.length === 1">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="col-md-6 mt-4">
                        <label class="form-label small fw-bold text-secondary">Condiciones de Alta / Observaciones Finales</label>
                        <textarea name="consideraciones_alta" class="form-control rounded-3" rows="2" placeholder="Observaciones de salida de la sala de hemodiálisis..."></textarea>
                    </div>
                    <div class="col-md-6 mt-4">
                        <label class="form-label small fw-bold text-secondary">Pendientes Clínicos Próxima Sesión</label>
                        <textarea name="pendientes" class="form-control rounded-3" rows="2" placeholder="Ej: Control cinético de urea, nueva serología bimensual..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light py-3 text-end rounded-bottom-4">
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i>Registrar Ficha e Inyectar a Historial
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicialización de selectores estilizados limpios sin modales
        new TomSelect('.id-select-ts', { create: false });
        new TomSelect('.order-select-ts', { create: false });
    });
</script>
@endpush