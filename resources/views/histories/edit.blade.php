@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" x-data="{ 
    tieneBiopsia: '{{ old('biopsia_renal', $history->biopsia_renal ? '1' : '0') }}',
    tieneOtrosAccesos: '{{ old('o_tipos', $history->o_tipos) ? '1' : '0' }}',
    enfCronica: '{{ old('enf_cronica', $history->enf_cronica ?? '') }}',
    enfAguda: '{{ old('enf_aguda', $history->enf_aguda ?? '') }}'
}">
    
    <div class="mb-3">
        <a href="{{ route('histories.index') }}" class="text-decoration-none text-secondary small fw-semibold">
            <i class="fa-solid fa-chevron-left me-1"></i> Cancelar y Volver a la Orden
        </a>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-dark py-3 px-4 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-1 text-white fw-bold"><i class="fa-solid fa-file-medical text-info me-2"></i>Historia Clínica Digital de Ingreso</h5>
                <p class="text-muted small mb-0">Paciente: <span class="text-black fw-semibold">{{ $history->patient->nombre ?? 'N/A' }}</span></p>
            </div>
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">Orden #{{ $history->order->codigo }}</span>
        </div>

        <div class="card-body p-4 bg-light-subtle">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-triangle-exclamation fs-4 me-3"></i>
                        <div>
                            <strong>¡Atención! Hay errores en el formulario:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('histories.update', $history->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Navegación de Pestañas Hospitalarias -->
                <ul class="nav nav-pills nav-justified mb-4 p-1 bg-light rounded-3 shadow-sm border" id="historyTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active rounded-2 fw-semibold py-2" id="t1" data-bs-toggle="tab" data-bs-target="#tab-anamnesis" type="button" role="tab">1. Anamnesis</button></li>
                    <li class="nav-item"><button class="nav-link rounded-2 fw-semibold py-2" id="t2" data-bs-toggle="tab" data-bs-target="#tab-antecedentes" type="button" role="tab">2. Antecedentes Específicos</button></li>
                    <li class="nav-item"><button class="nav-link rounded-2 fw-semibold py-2" id="t3" data-bs-toggle="tab" data-bs-target="#tab-accesos" type="button" role="tab">3. Accesos Vasculares</button></li>
                    <li class="nav-item"><button class="nav-link rounded-2 fw-semibold py-2" id="t4" data-bs-toggle="tab" data-bs-target="#tab-examen" type="button" role="tab">4. Examen Físico</button></li>
                    <li class="nav-item"><button class="nav-link rounded-2 fw-semibold py-2" id="t5" data-bs-toggle="tab" data-bs-target="#tab-serologia" type="button" role="tab">5. Serología / Vacunas</button></li>
                    <li class="nav-item"><button class="nav-link rounded-2 fw-semibold py-2" id="t6" data-bs-toggle="tab" data-bs-target="#tab-diagnosticos" type="button" role="tab">6. Juicio Clínico / Alta</button></li>
                </ul>

                <div class="tab-content bg-white p-4 rounded-4 border shadow-sm" id="historyTabsContent">
                    
                    <!-- PESTAÑA 1: ANAMNESIS Y ENFERMEDAD ACTUAL -->
                    <div class="tab-pane fade show active" id="tab-anamnesis" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Fecha Ingreso HD *</label>
                                <input type="date" class="form-control" name="fecha_ingreso_hd" value="{{ old('fecha_ingreso_hd', $history->fecha_ingreso_hd ? $history->fecha_ingreso_hd->format('Y-m-d') : '') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Servicio de Origen</label>
                                <select class="form-select" name="serv_origen">
                                    <option value="">Seleccione servicio</option>
                                    @foreach(['URO','TOPI','TOP 2','OBS','UCI','UCIN','URPA','MED','CIRUG','GIN','PED','UCIN-NEO','C. EXT','URCA'] as $servicio)
                                        <option value="{{ $servicio }}" {{ old('serv_origen', $history->serv_origen) === $servicio ? 'selected' : '' }}>{{ $servicio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Cama</label>
                                <input type="text" class="form-control" name="cama" value="{{ old('cama', $history->cama) }}" maxlength="25">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Inicio</label>
                                <input type="text" class="form-control" name="inicio_enfermedad" value="{{ old('inicio_enfermedad', $history->inicio_enfermedad) }}" placeholder="Súbito / Insidioso">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Curso</label>
                                <input type="text" class="form-control" name="curso_enfermedad" value="{{ old('curso_enfermedad', $history->curso_enfermedad) }}" placeholder="Progresivo / Estacionario">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Apetito</label>
                                <input type="text" class="form-control" name="apetito" value="{{ old('apetito', $history->apetito) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Sed</label>
                                <input type="text" class="form-control" name="sed" value="{{ old('sed', $history->sed) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-secondary">Heces</label>
                                <input type="text" class="form-control" name="heces" value="{{ old('heces', $history->heces) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Sueño</label>
                                <input type="text" class="form-control" name="sueno" value="{{ old('sueno', $history->sueno) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary">Diuresis al momento del Ingreso</label>
                                <input type="text" class="form-control" name="diuresis_ingreso" value="{{ old('diuresis_ingreso', $history->diuresis_ingreso) }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-secondary">Relato Cronológico</label>
                                <textarea class="form-control" name="relato_cronologico" rows="4">{{ old('relato_cronologico', $history->relato_cronologico) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- PESTAÑA 2: ANTECEDENTES PERSONALES CON AÑO Dx Y MEDICACIÓN INDIVIDUALIZADA (IMAGE_2489C1.PNG) -->
                    <!-- PESTAÑA 2: ANTECEDENTES PERSONALES EN 3 COLUMNAS PRESET (IMAGE_2489C1.PNG) -->
<div class="tab-pane fade" id="tab-antecedentes" role="tabpanel">
    <div class="p-4 bg-light rounded-4 border mb-4">
        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
            <h6 class="fw-bold text-primary mb-0">
                <i class="fa-solid fa-table-list me-2"></i>Matriz de Antecedentes Personales e Historial Farmacológico
            </h6>
        </div>
        
        @php
            // Bloque 1 (Columna Izquierda de la imagen)
            $col1 = [
                'diabetes'          => 'DIABETES MELLITUS',
                'hta'               => 'HIPERTENSIÓN',
                'enfermedad_cv'     => 'ENFERMEDAD CV',
                'glomerulonefritis' => 'GLOMERULONEFRITIS',
                'vasculitis'        => 'VASCULITIS',
                'les'               => 'LES'
            ];

            // Bloque 2 (Columna Central de la imagen)
            $col2 = [
                'uropatia_obs'      => 'UROPATÍA OBSTRUCTIVA',
                'litiasis'          => 'LITIASIS URINARIA',
                'quistes_erpo'      => 'QUISTES / ERPQ',
                'tuberculosis'      => 'TUBERCULOSIS',
                'erc'               => 'ERC',
                'cirugias'          => 'CIRUGÍAS PREVIAS'
            ];

            // Bloque 3 (Columna Derecha de la imagen - Estilos de vida y Varios)
            $col3 = [
                'obesidad'          => 'OBESIDAD',
                'tabaquismo'        => 'TABAQUISMO',
                'alcoholismo'       => 'ALCOHOLISMO',
                'sedentarismo'      => 'SEDENTARISMO',
                'transfusiones'     => 'TRANSFUSIONES',
                'otras'             => 'OTRAS'
            ];

            $saved_data = (array)($history->antecedentes_personales ?? []);
        @endphp

        <!-- Grid Principal de 3 Columnas Responsivas -->
        <div class="row g-4 row-cols-1 row-cols-md-1 row-cols-lg-3">
            
            <!-- COLUMNA 1 -->
            <div class="col border-end-lg">
                <div class="row border-bottom pb-2 mb-2 text-secondary fw-bold small text-uppercase">
                    <div class="col-5">Patología</div>
                    <div class="col-3 text-center">Año Dx</div>
                    <div class="col-4">Medicación</div>
                </div>
                @foreach($col1 as $key => $label)
                    <div class="row align-items-center g-2 mb-2">
                        <div class="col-5 text-truncate" title="{{ $label }}"><span class="fw-semibold text-dark small">{{ $label }}</span></div>
                        <div class="col-3"><input type="text" class="form-control form-control-sm text-center" name="ant_data[{{ $key }}][anio]" value="{{ $saved_data[$key]['anio'] ?? '' }}" maxlength="4" placeholder="AAAA"></div>
                        <div class="col-4"><input type="text" class="form-control form-control-sm" name="ant_data[{{ $key }}][medicacion]" value="{{ $saved_data[$key]['medicacion'] ?? '' }}" placeholder="Detalle"></div>
                    </div>
                @endforeach
            </div>

            <!-- COLUMNA 2 -->
            <div class="col border-end-lg">
                <div class="row border-bottom pb-2 mb-2 text-secondary fw-bold small text-uppercase">
                    <div class="col-5">Patología</div>
                    <div class="col-3 text-center">Año Dx</div>
                    <div class="col-4">Medicación</div>
                </div>
                @foreach($col2 as $key => $label)
                    <div class="row align-items-center g-2 mb-2">
                        <div class="col-5 text-truncate" title="{{ $label }}"><span class="fw-semibold text-dark small">{{ $label }}</span></div>
                        <div class="col-3"><input type="text" class="form-control form-control-sm text-center" name="ant_data[{{ $key }}][anio]" value="{{ $saved_data[$key]['anio'] ?? '' }}" maxlength="4" placeholder="AAAA"></div>
                        <div class="col-4"><input type="text" class="form-control form-control-sm" name="ant_data[{{ $key }}][medicacion]" value="{{ $saved_data[$key]['medicacion'] ?? '' }}" placeholder="Detalle"></div>
                    </div>
                @endforeach
            </div>

            <!-- COLUMNA 3 -->
            <div class="col">
                <div class="row border-bottom pb-2 mb-2 text-secondary fw-bold small text-uppercase">
                    <div class="col-5">Hábito / Estado</div>
                    <div class="col-3 text-center">Año Dx</div>
                    <div class="col-4">Observación</div>
                </div>
                @foreach($col3 as $key => $label)
                    <div class="row align-items-center g-2 mb-2">
                        <div class="col-5 text-truncate" title="{{ $label }}"><span class="fw-semibold text-dark small">{{ $label }}</span></div>
                        <div class="col-3"><input type="text" class="form-control form-control-sm text-center" name="ant_data[{{ $key }}][anio]" value="{{ $saved_data[$key]['anio'] ?? '' }}" maxlength="4" placeholder="AAAA"></div>
                        <div class="col-4"><input type="text" class="form-control form-control-sm" name="ant_data[{{ $key }}][medicacion]" value="{{ $saved_data[$key]['medicacion'] ?? '' }}" placeholder="Detalle"></div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">Alergias Conocidas</label>
            <input type="text" class="form-control" name="alergias" value="{{ old('alergias', $history->alergias) }}" placeholder="Ej: Penicilina, Ninguna">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold text-secondary">Antecedentes Familiares Relevantes</label>
            <input type="text" class="form-control" name="antecedentes_familiares" value="{{ old('antecedentes_familiares', $history->antecedentes_familiares) }}">
        </div>
    </div>

    <div class="p-4 bg-light rounded-4 border mt-4">
        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
            <h6 class="fw-bold text-primary mb-0">
                <i class="fa-solid fa-vial-circle-check me-2"></i>Biopsia Renal
            </h6>
        </div>
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold text-secondary">¿Se realizó biopsia renal?</label>
                <select class="form-select" name="biopsia_renal" x-model="tieneBiopsia">
                    <option value="0">NO</option>
                    <option value="1">SÍ</option>
                </select>
            </div>
            <div class="col-md-3" x-show="tieneBiopsia === '1'" x-cloak>
                <label class="form-label fw-bold text-secondary">Año</label>
                <input type="text" class="form-control text-center" name="biopsia_renal_anio" value="{{ old('biopsia_renal_anio', $history->biopsia_renal_anio) }}" maxlength="4" placeholder="AAAA" :disabled="tieneBiopsia !== '1'">
            </div>
            <div class="col-md-6" x-show="tieneBiopsia === '1'" x-cloak>
                <label class="form-label fw-bold text-secondary">Resultado</label>
                <input type="text" class="form-control" name="biopsia_renal_resultado" value="{{ old('biopsia_renal_resultado', $history->biopsia_renal_resultado) }}" placeholder="Resultado de la biopsia" :disabled="tieneBiopsia !== '1'">
            </div>
        </div>
    </div>
</div>

                    <!-- PESTAÑA 3: ACCESOS VASCULARES (PRINCIPAL, SECUNDARIO Y OTROS) -->
                    <div class="tab-pane fade" id="tab-accesos" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <h6 class="fw-bold text-dark"><i class="fa-solid fa-circle-dot text-primary me-2"></i>Acceso Vascular Principal</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Tipo de Acceso</label>
                                    <select class="form-select select-sm" name="tipo">
                                        <option value="">Ninguno</option>
                                        @foreach(['CVC TUNELIZADO','CVC TEMPORAL','FAV','INJERTO'] as $t)
                                            <option value="{{ $t }}" {{ old('tipo', $history->tipo) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Localización</label>
                                    <select class="form-select select-sm" name="localizacion">
                                        <option value="">Ninguna</option>
                                        @foreach(['RADIAL','HUMERAL','CERVICAL','FEMORAL','OTROS'] as $l)
                                            <option value="{{ $l }}" {{ old('localizacion', $history->localizacion) == $l ? 'selected' : '' }}>{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Lado</label>
                                    <select class="form-select select-sm" name="lado">
                                        <option value="">Ninguno</option>
                                        <option value="DERECHA" {{ old('lado', $history->lado) == 'DERECHA' ? 'selected' : '' }}>DERECHA</option>
                                        <option value="IZQUIERDA" {{ old('lado', $history->lado) == 'IZQUIERDA' ? 'selected' : '' }}>IZQUIERDA</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Estado Funcional</label>
                                    <select class="form-select select-sm" name="estado">
                                        <option value="">Seleccione...</option>
                                        <option value="BUENO" {{ old('estado', $history->estado) == 'BUENO' ? 'selected' : '' }}>BUENO</option>
                                        <option value="REGULAR" {{ old('estado', $history->estado) == 'REGULAR' ? 'selected' : '' }}>REGULAR</option>
                                        <option value="MALO" {{ old('estado', $history->estado) == 'MALO' ? 'selected' : '' }}>MALO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left me-2"></i>Terapias y Retiros Previos</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">¿Diálisis Peritoneal?</label>
                                    <select class="form-select select-sm" name="d_peritoneal">
                                        <option value="0" {{ old('d_peritoneal', $history->d_peritoneal) == 0 ? 'selected' : '' }}>NO</option>
                                        <option value="1" {{ old('d_peritoneal', $history->d_peritoneal) == 1 ? 'selected' : '' }}>SÍ</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">¿Trasplante Renal?</label>
                                    <select class="form-select select-sm" name="t_renal">
                                        <option value="0" {{ old('t_renal', $history->t_renal) == 0 ? 'selected' : '' }}>NO</option>
                                        <option value="1" {{ old('t_renal', $history->t_renal) == 1 ? 'selected' : '' }}>SÍ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Otros Accesos Previos</label>
                                    <input type="text" class="form-control form-control-sm" name="o_tipos" value="{{ old('o_tipos', $history->o_tipos) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Fecha de Cierre/Retiro</label>
                                    <input type="date" class="form-control form-control-sm" name="o_fecha" value="{{ old('o_fecha', $history->o_fecha ? \Carbon\Carbon::parse($history->o_fecha)->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Causa de Pérdida / Fallo</label>
                                    <input type="text" class="form-control form-control-sm" name="o_causa" value="{{ old('o_causa', $history->o_causa) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PESTAÑA 4: EXAMEN FÍSICO POR SISTEMAS -->
                    <div class="tab-pane fade" id="tab-examen" role="tabpanel">
                        <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-heart-pulse me-2"></i>Signos Vitales y Antropometría</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-2"><label class="form-label small text-muted mb-1">PA (mmHg)</label><input type="text" class="form-control" name="pa" value="{{ old('pa', $history->pa) }}"></div>
                            <div class="col-md-2"><label class="form-label small text-muted mb-1">FC (lpm)</label><input type="number" class="form-control" name="fc" value="{{ old('fc', $history->fc) }}"></div>
                            <div class="col-md-2"><label class="form-label small text-muted mb-1">FR (rpm)</label><input type="number" class="form-control" name="fr" value="{{ old('fr', $history->fr) }}"></div>
                            <div class="col-md-2"><label class="form-label small text-muted mb-1">SatO₂ (%)</label><input type="number" class="form-control" name="sat_o2" value="{{ old('sat_o2', $history->sat_o2) }}"></div>
                            <div class="col-md-2"><label class="form-label small text-muted mb-1">Peso (Kg)</label><input type="number" step="0.01" class="form-control" name="peso_ingreso" value="{{ old('peso_ingreso', $history->peso_ingreso) }}"></div>
                            <div class="col-md-2"><label class="form-label small text-muted mb-1">Talla (m)</label><input type="number" step="0.01" class="form-control" name="talla_ingreso" value="{{ old('talla_ingreso', $history->talla_ingreso) }}"></div>
                        </div>

                        <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-stethoscope me-2"></i>Evaluación de Sistemas Completa</h6>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label small text-muted mb-1">Aspecto General</label><input type="text" class="form-control" name="aspecto_general" value="{{ old('aspecto_general', $history->aspecto_general) }}"></div>
                            <div class="col-md-4"><label class="form-label small text-muted mb-1">Piel / Mucosas</label><input type="text" class="form-control" name="piel" value="{{ old('piel', $history->piel) }}"></div>
                            <div class="col-md-4"><label class="form-label small text-muted mb-1">TCSC (Edemas)</label><input type="text" class="form-control" name="tcsc" value="{{ old('tcsc', $history->tcsc) }}"></div>
                            <div class="col-md-6"><label class="form-label small text-muted mb-1">Aparato Respiratorio</label><textarea class="form-control" name="respiratorio" rows="2">{{ old('respiratorio', $history->respiratorio) }}</textarea></div>
                            <div class="col-md-6"><label class="form-label small text-muted mb-1">Aparato Cardiovascular</label><textarea class="form-control" name="cardiovascular" rows="2">{{ old('cardiovascular', $history->cardiovascular) }}</textarea></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Abdomen</label><input type="text" class="form-control" name="abdomen" value="{{ old('abdomen', $history->abdomen) }}"></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Génito Urinario</label><input type="text" class="form-control" name="g_urinario" value="{{ old('g_urinario', $history->g_urinario) }}"></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Neurológico</label><input type="text" class="form-control" name="neurologico" value="{{ old('neurologico', $history->neurologico) }}"></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Estado Nutricional</label><input type="text" class="form-control" name="e_nutricional" value="{{ old('e_nutricional', $history->e_nutricional) }}"></div>
                        </div>
                    </div>

                    <!-- PESTAÑA 5: SEROLOGÍA VIRAL Y VACUNAS -->
                    <div class="tab-pane fade" id="tab-serologia" role="tabpanel">
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-virus me-2"></i>Resultados Serológicos Obligatorios</h6>
                            <div class="row g-3">
                                @foreach(['hiv' => 'HIV', 'hbsag' => 'HBsAg (Hep B)', 'anti_hbc' => 'Anti-HBc', 'vhc' => 'VHC (Hep C)', 'anti_hbs' => 'Anti-HBs', 'rpr' => 'RPR / VDRL'] as $key => $label)
                                    <div class="col-md-4">
                                        <label class="form-label small text-dark fw-bold">{{ $label }}</label>
                                        <select class="form-select select-sm" name="{{ $key }}">
                                            <option value="0" {{ old($key, $history->$key) == 0 ? 'selected' : '' }}>No Reactivo / Negativo</option>
                                            <option value="1" {{ old($key, $history->$key) == 1 ? 'selected' : '' }}>Reactivo / Positivo</option>
                                        </select>
                                    </div>
                                @endforeach
                                <div class="col-md-12"><label class="form-label small text-muted mb-1">Observaciones / Otros Marcadores</label><input type="text" class="form-control" name="ningun_se" value="{{ old('ningun_se', $history->ningun_se) }}"></div>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-syringe me-2"></i>Inmunizaciones (Hepatitis)</h6>
                            <div class="row g-3">
                                <div class="col-md-4"><label class="form-label small text-muted mb-1">Dosis al Ingreso</label><input type="number" class="form-control" name="vacuna_ingreso" value="{{ old('vacuna_ingreso', $history->vacuna_ingreso) }}"></div>
                                <div class="col-md-4"><label class="form-label small text-muted mb-1">Dosis al Alta</label><input type="number" class="form-control" name="vacuna_alta" value="{{ old('vacuna_alta', $history->vacuna_alta) }}"></div>
                                <div class="col-md-4"><label class="form-label small text-muted mb-1">Otras Vacunas Registradas</label><input type="text" class="form-control" name="otras_vacunas" value="{{ old('otras_vacunas', $history->otras_vacunas) }}"></div>
                            </div>
                        </div>
                    </div>

                    <!-- PESTAÑA 6: JUICIO CLÍNICO Y CIERRE -->
                    <div class="tab-pane fade" id="tab-diagnosticos" role="tabpanel">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold text-warning mb-2">Enfermedad Renal Crónica (ERC)</h6>
                                <div class="mb-2"><label class="form-label small text-muted mb-0">Estadio (G/A)</label><select class="form-select" name="enf_cronica" x-model="enfCronica"><option value="">N/A</option><option value="G">Grado G</option><option value="A">Grado A</option></select></div>
                                <div class="mb-2"><label class="form-label small text-muted mb-0">Descripción</label><input type="text" class="form-control" name="descrip1" value="{{ old('descrip1', $history->descrip1) }}"></div>
                                <div><label class="form-label small text-muted mb-0">Etiología de Cronicidad</label><input type="text" class="form-control" name="etiologia_cronica" value="{{ old('etiologia_cronica', $history->etiologia_cronica) }}"></div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-danger mb-2">Lesión Renal Aguda (LRA)</h6>
                                <div class="mb-2"><label class="form-label small text-muted mb-0">Estadio KDIGO</label><select class="form-select" name="enf_aguda" x-model="enfAguda"><option value="">N/A</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></div>
                                <div class="mb-2"><label class="form-label small text-muted mb-0">Descripción</label><input type="text" class="form-control" name="descrip2" value="{{ old('descrip2', $history->descrip2) }}"></div>
                                <div><label class="form-label small text-muted mb-0">Etiología Aguda</label><input type="text" class="form-control" name="etiologia_aguda" value="{{ old('etiologia_aguda', $history->etiologia_aguda) }}"></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12"><label class="form-label fw-bold text-secondary">Motivo de Ingreso a Hemodiálisis / Hospitalización Actual</label><textarea class="form-control" name="motivo_hospt_act" rows="2">{{ old('motivo_hospt_act', $history->motivo_hospt_act) }}</textarea></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Peso Seco al Alta (Kg)</label><input type="number" step="0.01" class="form-control" name="peso_seco" value="{{ old('peso_seco', $history->peso_seco) }}"></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Diuresis Residual al Alta</label><input type="text" class="form-control" name="diuresis_alta" value="{{ old('diuresis_alta', $history->diuresis_alta) }}"></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Fecha de Alta</label><input type="date" class="form-control" name="f_alta" value="{{ old('f_alta', $history->f_alta ? \Carbon\Carbon::parse($history->f_alta)->format('Y-m-d') : '') }}"></div>
                            <div class="col-md-3"><label class="form-label small text-muted mb-1">Consideraciones / Esquema Propuesto</label><input type="text" class="form-control" name="consideraciones_alta" value="{{ old('consideraciones_alta', $history->consideraciones_alta) }}"></div>
                            <div class="col-md-6"><label class="form-label small text-muted mb-1">Causa de Deceso (Si aplica)</label><textarea class="form-control" name="motivo_fallece" rows="2">{{ old('motivo_fallece', $history->motivo_fallece) }}</textarea></div>
                            <div class="col-md-6"><label class="form-label small text-muted mb-1 text-danger fw-bold">Pendientes de Diagnóstico al Alta</label><textarea class="form-control border-danger-subtle" name="pendientes" rows="2">{{ old('pendientes', $history->pendientes) }}</textarea></div>
                        </div>
                    </div>

                </div>

                <!-- Botoneras de Envío -->
                <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small italic"><i class="fa-solid fa-cloud-arrow-up text-primary me-1"></i> Todos los campos se guardarán estructurados en la matriz JSON del expediente.</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('histories.index') }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar Ficha Histórica Unificada</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .nav-pills .nav-link { color: var(--bs-secondary-color); background-color: transparent; transition: all 0.2s ease-in-out; }
    .nav-pills .nav-link.active { color: #ffffff !important; background-color: #014f86 !important; box-shadow: 0 4px 10px rgba(1, 79, 134, 0.2); }
    .form-control:focus, .form-select:focus { border-color: #014f86; box-shadow: 0 0 0 0.2px rgba(1, 79, 134, 0.25); }
    @media (min-width: 992px) {
    .border-end-lg {
        border-end: 1px solid #dee2e6 !important;
        padding-right: 1.5rem;
    }
}
</style>
@endpush