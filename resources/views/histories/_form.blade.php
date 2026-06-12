@php
    $history = $history ?? null;
    $isEdit = (bool) $history;
    $textValue = fn (string $field, $default = '') => old($field, $history?->{$field} ?? $default);
    $dateValue = function (string $field, $default = null) use ($history) {
        $value = old($field);
        if ($value !== null) {
            return $value;
        }
        $modelValue = $history?->{$field};
        return $modelValue ? $modelValue->format('Y-m-d') : $default;
    };
    $selected = fn (string $field, string $value, $default = null) => (string) old($field, $history?->{$field} ?? $default) === $value ? 'selected' : '';
    $checked = fn (string $field) => old($field, $history?->{$field} ?? false) ? 'checked' : '';
@endphp

<div class="d-flex gap-2 mb-3 overflow-x-auto pb-2">
    <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'general' ? 'btn-primary' : 'btn-light border'" @click="tab = 'general'">1. General e Ingreso</button>
    <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'anamnesis' ? 'btn-primary' : 'btn-light border'" @click="tab = 'anamnesis'">2. Anamnesis y Sistemas</button>
    <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'vascular' ? 'btn-primary' : 'btn-light border'" @click="tab = 'vascular'">3. Acceso Vascular</button>
    <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'serologia' ? 'btn-primary' : 'btn-light border'" @click="tab = 'serologia'">4. Serología y Diagnósticos</button>
    <button type="button" class="btn btn-sm rounded-pill px-3" :class="tab === 'alta' ? 'btn-primary' : 'btn-light border'" @click="tab = 'alta'">5. Alta y Pendientes</button>
</div>

@if ($errors->any())
    <div class="alert alert-danger rounded-3 shadow-sm">
        <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i>Corrige los campos señalados.</div>
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm border-0 mb-4" x-show="tab === 'general'">
    <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-hospital-user me-2 text-primary"></i>Datos de Admisión de Especialidad</div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Paciente Evaluado</label>
                <select name="patient_id" class="form-select rounded-3 js-history-select" required>
                    <option value="">-- SELECCIONE --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ (int) old('patient_id', $history?->patient_id) === $patient->id ? 'selected' : '' }}>{{ $patient->nombre }}{{ $patient->dni ? ' - DNI '.$patient->dni : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Orden Médica Vinculada</label>
                <select name="order_id" class="form-select rounded-3 js-history-select" required>
                    <option value="">-- SELECCIONE --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ (int) old('order_id', $history?->order_id) === $order->id ? 'selected' : '' }}>Orden #{{ $order->id }}{{ $order->codigo ? ' - '.$order->codigo : '' }}{{ $order->fecha ? ' - '.$order->fecha->format('d/m/Y') : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Fecha de Ingreso a HD</label>
                <input type="date" name="fecha_ingreso_hd" class="form-control rounded-3" value="{{ $dateValue('fecha_ingreso_hd', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Servicio de Origen</label>
                <input type="text" name="serv_origen" class="form-control rounded-3" value="{{ $textValue('serv_origen') }}" maxlength="25">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Tiempo Enfermedad</label>
                <input type="text" name="tiempo_enfermedad" class="form-control rounded-3" value="{{ $textValue('tiempo_enfermedad') }}" maxlength="50">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Forma de Inicio</label>
                <select name="inicio_enfermedad" class="form-select rounded-3">
                    <option value="">-- SELECCIONE --</option>
                    <option value="INSIDIOSO" {{ $selected('inicio_enfermedad', 'INSIDIOSO') }}>INSIDIOSO</option>
                    <option value="SÚBITO" {{ $selected('inicio_enfermedad', 'SÚBITO') }}>SÚBITO</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Curso evolutivo</label>
                <select name="curso_enfermedad" class="form-select rounded-3">
                    <option value="">-- SELECCIONE --</option>
                    <option value="PROGRESIVO" {{ $selected('curso_enfermedad', 'PROGRESIVO') }}>PROGRESIVO</option>
                    <option value="ESTACIONARIO" {{ $selected('curso_enfermedad', 'ESTACIONARIO') }}>ESTACIONARIO</option>
                    <option value="INTERMITENTE" {{ $selected('curso_enfermedad', 'INTERMITENTE') }}>INTERMITENTE</option>
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
                <textarea name="relato_cronologico" class="form-control rounded-3" rows="3">{{ $textValue('relato_cronologico') }}</textarea>
            </div>
            @foreach(['apetito' => 'Apetito', 'sed' => 'Sed', 'heces' => 'Heces', 'sueno' => 'Sueño'] as $field => $label)
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-secondary">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" class="form-control rounded-3" value="{{ $textValue($field) }}" maxlength="30">
                </div>
            @endforeach
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Diuresis de Ingreso</label>
                <input type="text" name="diuresis_ingreso" class="form-control rounded-3" value="{{ $textValue('diuresis_ingreso') }}" maxlength="50">
            </div>
            <div class="col-md-6 border-end pe-4">
                <h6 class="fw-bold text-dark mb-3">Antecedentes Clínicos Personales</h6>
                <div class="form-check form-switch mb-2"><input class="form-check-input" type="checkbox" x-model="antecedentes.diabetes"><label class="form-check-label small">Diabetes Mellitus</label></div>
                <div class="form-check form-switch mb-2"><input class="form-check-input" type="checkbox" x-model="antecedentes.hta"><label class="form-check-label small">Hipertensión Arterial</label></div>
                <div class="mt-2"><label class="small text-muted mb-1">Medicación Previa Recibida</label><input type="text" x-model="antecedentes.medicacion_previa" class="form-control form-control-sm rounded-3"></div>
                <input type="hidden" name="antecedentes_personales" :value="JSON.stringify(antecedentes)">
            </div>
            <div class="col-md-6 ps-4">
                <h6 class="fw-bold text-dark mb-2">Examen Físico Funcional</h6>
                <div class="row g-2">
                    <div class="col-md-4"><label class="small text-muted">P.A. (mmHg)</label><input type="text" name="pa" class="form-control form-control-sm rounded-3" value="{{ $textValue('pa') }}" maxlength="15"></div>
                    <div class="col-md-4"><label class="small text-muted">F.C. (Lpm)</label><input type="number" name="fc" class="form-control form-control-sm rounded-3" value="{{ $textValue('fc') }}"></div>
                    <div class="col-md-4"><label class="small text-muted">F.R. (Rpm)</label><input type="number" name="fr" class="form-control form-control-sm rounded-3" value="{{ $textValue('fr') }}"></div>
                    <div class="col-md-4"><label class="small text-muted">Sat O₂ (%)</label><input type="number" name="sat_o2" class="form-control form-control-sm rounded-3" value="{{ $textValue('sat_o2') }}"></div>
                    <div class="col-md-4"><label class="small text-muted">Peso (Kg)</label><input type="number" step="0.01" name="peso_ingreso" class="form-control form-control-sm rounded-3" value="{{ $textValue('peso_ingreso') }}"></div>
                    <div class="col-md-4"><label class="small text-muted">Talla (m)</label><input type="number" step="0.01" name="talla_ingreso" class="form-control form-control-sm rounded-3" value="{{ $textValue('talla_ingreso') }}"></div>
                    <div class="col-md-4"><label class="small text-muted">FiO₂</label><input type="number" step="0.01" name="fio" class="form-control form-control-sm rounded-3" value="{{ $textValue('fio') }}"></div>
                </div>
            </div>
            @foreach(['antecedentes_familiares' => 'Antecedentes Familiares', 'alergias' => 'Alergias', 'aspecto_general' => 'Aspecto General', 'piel' => 'Piel', 'tcsc' => 'TCSC', 'respiratorio' => 'Respiratorio', 'cardiovascular' => 'Cardiovascular'] as $field => $label)
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary">{{ $label }}</label>
                    <textarea name="{{ $field }}" class="form-control rounded-3" rows="2">{{ $textValue($field) }}</textarea>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" x-show="tab === 'vascular'">
    <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-code-fork me-2 text-primary"></i>Acceso Vascular, Biopsia y Terapias Previas</div>
    <div class="card-body p-4">
        <div class="row g-3">
            @foreach([['tipo','Tipo de Acceso', ['CVC TUNELIZADO','CVC TEMPORAL','FAV','INJERTO']], ['localizacion','Localización', ['RADIAL','HUMERAL','CERVICAL','FEMORAL','OTROS']], ['lado','Lado', ['DERECHA','IZQUIERDA']], ['estado','Estado', ['BUENO','REGULAR','MALO']]] as [$field, $label, $options])
                <div class="col-md-3"><label class="form-label small fw-bold text-secondary">{{ $label }}</label><select name="{{ $field }}" class="form-select rounded-3"><option value="">-- SELECCIONE --</option>@foreach($options as $option)<option value="{{ $option }}" {{ $selected($field, $option) }}>{{ $option }}</option>@endforeach</select></div>
            @endforeach
            <div class="col-md-12"><hr class="my-2"><h6 class="fw-bold text-secondary">Acceso Vascular Secundario</h6></div>
            @foreach([['tipo2','Tipo de Acceso 2', ['CVC TUNELIZADO','CVC TEMPORAL','FAV','INJERTO']], ['localizacion2','Localización 2', ['RADIAL','HUMERAL','CERVICAL','FEMORAL','OTROS']], ['lado2','Lado 2', ['DERECHA','IZQUIERDA']]] as [$field, $label, $options])
                <div class="col-md-4"><label class="form-label small fw-bold text-secondary">{{ $label }}</label><select name="{{ $field }}" class="form-select rounded-3"><option value="">-- SELECCIONE --</option>@foreach($options as $option)<option value="{{ $option }}" {{ $selected($field, $option) }}>{{ $option }}</option>@endforeach</select></div>
            @endforeach
            <div class="col-md-12"><hr class="my-2"></div>
            <div class="col-md-3 pt-4"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="biopsia_renal" x-model="biopsia" value="1"><label class="form-check-label small fw-bold text-dark">¿Posee Biopsia Renal?</label></div></div>
            <div class="col-md-3" x-show="biopsia"><label class="form-label small fw-bold text-secondary">Año Biopsia</label><input type="text" name="biopsia_renal_anio" class="form-control rounded-3" value="{{ $textValue('biopsia_renal_anio') }}" maxlength="4"></div>
            <div class="col-md-6" x-show="biopsia"><label class="form-label small fw-bold text-secondary">Resultado Biopsia</label><input type="text" name="biopsia_renal_resultado" class="form-control rounded-3" value="{{ $textValue('biopsia_renal_resultado') }}" maxlength="255"></div>
            <div class="col-md-3"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="d_peritoneal" value="1" {{ $checked('d_peritoneal') }}><label class="form-check-label small">Diálisis peritoneal previa</label></div></div>
            <div class="col-md-3"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="t_renal" value="1" {{ $checked('t_renal') }}><label class="form-check-label small">Trasplante renal previo</label></div></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Otros tipos</label><input type="text" name="o_tipos" class="form-control rounded-3" value="{{ $textValue('o_tipos') }}" maxlength="50"></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Fecha</label><input type="date" name="o_fecha" class="form-control rounded-3" value="{{ $dateValue('o_fecha') }}"></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Causa</label><input type="text" name="o_causa" class="form-control rounded-3" value="{{ $textValue('o_causa') }}" maxlength="100"></div>
            @foreach(['abdomen' => 'Abdomen', 'g_urinario' => 'G. Urinario', 'neurologico' => 'Neurológico', 'e_nutricional' => 'E. Nutricional'] as $field => $label)
                <div class="col-md-3"><label class="form-label small fw-bold text-secondary">{{ $label }}</label><input type="text" name="{{ $field }}" class="form-control rounded-3" value="{{ $textValue($field) }}" maxlength="100"></div>
            @endforeach
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" x-show="tab === 'serologia'">
    <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-virus-covid me-2 text-primary"></i>Serología, Vacunas y Diagnósticos</div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-12">
                <h6 class="fw-bold text-secondary small mb-2">Marcadores Serológicos Activos</h6>
                <div class="d-flex flex-wrap gap-4 border p-3 rounded-3 bg-light">
                    @foreach(['hiv' => 'VIH (+)', 'hbsag' => 'HBsAg', 'anti_hbc' => 'Anti-HBc', 'vhc' => 'VHC', 'anti_hbs' => 'Anti-HBs', 'rpr' => 'RPR'] as $field => $label)
                        <div class="form-check"><input class="form-check-input" type="checkbox" name="{{ $field }}" value="1" {{ $checked($field) }}><label class="form-check-label small">{{ $label }}</label></div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4"><label class="form-label small fw-bold text-secondary">Serología sin reactivos</label><input type="text" name="ningun_se" class="form-control rounded-3" value="{{ $textValue('ningun_se', 'NINGUNO') }}" maxlength="50"></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Vacuna ingreso</label><input type="number" name="vacuna_ingreso" class="form-control rounded-3" value="{{ $textValue('vacuna_ingreso', 0) }}"></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Vacuna alta</label><input type="number" name="vacuna_alta" class="form-control rounded-3" value="{{ $textValue('vacuna_alta', 0) }}"></div>
            <div class="col-md-4"><label class="form-label small fw-bold text-secondary">Otras vacunas</label><input type="text" name="otras_vacunas" class="form-control rounded-3" value="{{ $textValue('otras_vacunas') }}" maxlength="200"></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Enf. crónica</label><select name="enf_cronica" class="form-select rounded-3"><option value="">--</option><option value="G" {{ $selected('enf_cronica', 'G') }}>G</option><option value="A" {{ $selected('enf_cronica', 'A') }}>A</option></select></div>
            <div class="col-md-4"><label class="form-label small fw-bold text-secondary">Descripción crónica</label><input type="text" name="descrip1" class="form-control rounded-3" value="{{ $textValue('descrip1') }}" maxlength="50"></div>
            <div class="col-md-6"><label class="form-label small fw-bold text-secondary">Etiología crónica</label><input type="text" name="etiologia_cronica" class="form-control rounded-3" value="{{ $textValue('etiologia_cronica') }}" maxlength="200"></div>
            <div class="col-md-2"><label class="form-label small fw-bold text-secondary">Enf. aguda</label><select name="enf_aguda" class="form-select rounded-3"><option value="">--</option><option value="1" {{ $selected('enf_aguda', '1') }}>1</option><option value="2" {{ $selected('enf_aguda', '2') }}>2</option><option value="3" {{ $selected('enf_aguda', '3') }}>3</option></select></div>
            <div class="col-md-4"><label class="form-label small fw-bold text-secondary">Descripción aguda</label><input type="text" name="descrip2" class="form-control rounded-3" value="{{ $textValue('descrip2') }}" maxlength="50"></div>
            <div class="col-md-6"><label class="form-label small fw-bold text-secondary">Etiología aguda</label><input type="text" name="etiologia_aguda" class="form-control rounded-3" value="{{ $textValue('etiologia_aguda') }}" maxlength="200"></div>
            <div class="col-md-12"><label class="form-label small fw-bold text-secondary">Motivo hospitalización actual</label><textarea name="motivo_hospt_act" class="form-control rounded-3" rows="2">{{ $textValue('motivo_hospt_act') }}</textarea></div>
            <div class="col-md-12 mt-2">
                <div class="d-flex justify-content-between align-items-center mb-2"><h6 class="fw-bold text-dark mb-0">Impresión Diagnóstica CIE-10</h6><button type="button" class="btn btn-xs btn-outline-primary rounded-2 px-2" @click="addDiag()"><i class="fa-solid fa-plus-circle me-1"></i>Agregar Línea</button></div>
                <input type="hidden" name="diagnostico" :value="JSON.stringify(diagnosticos)">
                <template x-for="(diag, idx) in diagnosticos" :key="idx">
                    <div class="row g-2 mb-2 align-items-center"><div class="col-md-3"><input type="text" x-model="diag.cie10" class="form-control form-control-sm rounded-3" placeholder="Código CIE-10"></div><div class="col-md-8"><input type="text" x-model="diag.descripcion" class="form-control form-control-sm rounded-3" placeholder="Descripción extendida"></div><div class="col-md-1 text-center"><button type="button" class="btn btn-sm btn-light text-danger border" @click="removeDiag(idx)" :disabled="diagnosticos.length === 1"><i class="fa-solid fa-trash-can"></i></button></div></div>
                </template>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" x-show="tab === 'alta'">
    <div class="card-header bg-white py-3 fw-bold text-dark"><i class="fa-solid fa-clipboard-check me-2 text-primary"></i>Alta, Pendientes y Condiciones Finales</div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label small fw-bold text-secondary">Fecha de alta</label><input type="date" name="f_alta" class="form-control rounded-3" value="{{ $dateValue('f_alta') }}"></div>
            <div class="col-md-3"><label class="form-label small fw-bold text-secondary">Peso seco</label><input type="number" step="0.01" name="peso_seco" class="form-control rounded-3" value="{{ $textValue('peso_seco') }}"></div>
            <div class="col-md-6"><label class="form-label small fw-bold text-secondary">Diuresis de alta</label><input type="text" name="diuresis_alta" class="form-control rounded-3" value="{{ $textValue('diuresis_alta') }}" maxlength="50"></div>
            <div class="col-md-6"><label class="form-label small fw-bold text-secondary">Consideraciones de Alta</label><textarea name="consideraciones_alta" class="form-control rounded-3" rows="2">{{ $textValue('consideraciones_alta') }}</textarea></div>
            <div class="col-md-6"><label class="form-label small fw-bold text-secondary">Pendientes Clínicos Próxima Sesión</label><textarea name="pendientes" class="form-control rounded-3" rows="2">{{ $textValue('pendientes') }}</textarea></div>
            <div class="col-md-12"><label class="form-label small fw-bold text-secondary">Motivo de fallecimiento</label><textarea name="motivo_fallece" class="form-control rounded-3" rows="2">{{ $textValue('motivo_fallece') }}</textarea></div>
        </div>
    </div>
    <div class="card-footer bg-light py-3 text-end rounded-bottom-4">
        <button type="submit" class="btn {{ $isEdit ? 'btn-info text-white' : 'btn-primary' }} px-5 rounded-3 fw-bold shadow-sm">
            <i class="fa-solid {{ $isEdit ? 'fa-square-check' : 'fa-cloud-arrow-up' }} me-2"></i>{{ $isEdit ? 'Actualizar Historia Clínica' : 'Registrar Historia Clínica' }}
        </button>
    </div>
</div>
