@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0" style="color: #0f3057;">Registro Médico de Hemodiálisis</h2>
            <p class="text-muted small mb-0">Paciente: <span class="fw-bold">{{ $medical->patient->nombre ?? 'N/A' }}</span> | N° Orden: <span class="fw-bold">{{ $medical->order->codigo ?? 'N/A' }}</span></p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <span><i class="fa-solid fa-file-medical me-2 text-info"></i>Detalles del Registro</span>
        </div>
        <div class="card-body">
            <form action="{{ route('medicals.update', $medical->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Datos de Sesión -->
                <fieldset class="border rounded p-3 mb-4">
                    <legend class="float-none w-auto px-2 fs-6 fw-bold text-primary">Datos Generales</legend>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Nro Sesión</label>
                            <input type="text" name="numero_sesion" value="{{ old('numero_sesion', $medical->numero_sesion) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Fecha Sesión <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_sesion" value="{{ old('fecha_sesion', $medical->fecha_sesion?->format('Y-m-d')) }}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Servicio Procedencia</label>
                            <input type="text" name="servicio_procedencia" value="{{ old('servicio_procedencia', $medical->servicio_procedencia) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Cama</label>
                            <input type="text" name="cama" value="{{ old('cama', $medical->cama) }}" class="form-control">
                        </div>
                    </div>
                </fieldset>

                <!-- Evaluación Clínica -->
                <fieldset class="border rounded p-3 mb-4">
                    <legend class="float-none w-auto px-2 fs-6 fw-bold text-primary">Evaluación Clínica Inicial</legend>
                    <div class="row g-3 mb-3">
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-secondary small">P.A. (mmHg)</label>
                            <input type="text" name="pa" value="{{ old('pa', $medical->pa) }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-secondary small">F.C. (x min)</label>
                            <input type="text" name="fc" value="{{ old('fc', $medical->fc) }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-secondary small">F.R. (x min)</label>
                            <input type="text" name="fr" value="{{ old('fr', $medical->fr) }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-secondary small">Sat. O2 (%)</label>
                            <input type="text" name="sat" value="{{ old('sat', $medical->sat) }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-secondary small">Peso Seco (Kg)</label>
                            <input type="number" step="0.01" name="peso_seco" value="{{ old('peso_seco', $medical->peso_seco) }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium text-secondary small">Diuresis</label>
                            <input type="text" name="diuresis" value="{{ old('diuresis', $medical->diuresis) }}" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <div class="form-check mt-2">
                                <input type="hidden" name="alergias" value="0">
                                <input type="checkbox" name="alergias" value="1" {{ old('alergias', $medical->alergias) ? 'checked' : '' }} class="form-check-input" id="alergiasCheck">
                                <label class="form-check-label fw-medium text-secondary small" for="alergiasCheck">¿Paciente presenta alergias?</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Evaluación General Médica</label>
                            <textarea name="evaluacion" rows="2" class="form-control">{{ old('evaluacion', $medical->evaluacion) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-secondary small">Descripción de Alergias</label>
                            <textarea name="alergias_descripcion" rows="2" class="form-control">{{ old('alergias_descripcion', $medical->alergias_descripcion) }}</textarea>
                        </div>
                    </div>
                </fieldset>

                <!-- Prescripción y Máquina -->
                <fieldset class="border rounded p-3 mb-4">
                    <legend class="float-none w-auto px-2 fs-6 fw-bold text-primary">Prescripción de Hemodiálisis</legend>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Técnica</label><input type="text" name="tecnica" value="{{ old('tecnica', $medical->tecnica) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Frecuencia</label><input type="text" name="frecuencia" value="{{ old('frecuencia', $medical->frecuencia) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Acceso</label><input type="text" name="acceso" value="{{ old('acceso', $medical->acceso) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Heparina</label><input type="text" name="heparina" value="{{ old('heparina', $medical->heparina) }}" class="form-control"></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Filtro</label><input type="text" name="filtro" value="{{ old('filtro', $medical->filtro) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Membrana</label><input type="text" name="membrana" value="{{ old('membrana', $medical->membrana) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Flujo Sangre (Qb)</label><input type="number" name="qb" value="{{ old('qb', $medical->qb) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Flujo Dialisato (Qd)</label><input type="number" name="qd" value="{{ old('qd', $medical->qd) }}" class="form-control"></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Tiempo Prog. (Hrs)</label><input type="number" name="tiempo_horas" value="{{ old('tiempo_horas', $medical->tiempo_horas) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Sodio (mEq/L)</label><input type="number" name="sodio_mEq" value="{{ old('sodio_mEq', $medical->sodio_mEq) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Perfil de Sodio</label><input type="text" name="perfil_sodio" value="{{ old('perfil_sodio', $medical->perfil_sodio) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">TD/LD</label><input type="text" name="tdld" value="{{ old('tdld', $medical->tdld) }}" class="form-control"></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">UF Total</label><input type="text" name="uft" value="{{ old('uft', $medical->uft) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">UF Aislada</label><input type="text" name="uf_asilada" value="{{ old('uf_asilada', $medical->uf_asilada) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">Perfil UF</label><input type="text" name="perfil_uf" value="{{ old('perfil_uf', $medical->perfil_uf) }}" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label fw-medium text-secondary small">UF Efectiva</label><input type="text" name="uf_efectivo" value="{{ old('uf_efectivo', $medical->uf_efectivo) }}" class="form-control"></div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-medium text-secondary small">Grado de Dependencia</label>
                            <select name="grado_dep" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="I" {{ old('grado_dep', $medical->grado_dep) == 'I' ? 'selected' : '' }}>I</option>
                                <option value="II" {{ old('grado_dep', $medical->grado_dep) == 'II' ? 'selected' : '' }}>II</option>
                                <option value="III" {{ old('grado_dep', $medical->grado_dep) == 'III' ? 'selected' : '' }}>III</option>
                                <option value="IV" {{ old('grado_dep', $medical->grado_dep) == 'IV' ? 'selected' : '' }}>IV</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium text-secondary small">Grupo / Factor</label>
                            <input type="text" name="grup_fact" value="{{ old('grup_fact', $medical->grup_fact) }}" class="form-control">
                        </div>
                        <div class="col-md-4 d-flex align-items-end mb-2">
                            <div class="form-check">
                                <input type="hidden" name="transfuciones" value="0">
                                <input type="checkbox" name="transfuciones" value="1" {{ old('transfuciones', $medical->transfuciones) ? 'checked' : '' }} class="form-check-input" id="transfucionesCheck">
                                <label class="form-check-label fw-medium text-secondary small" for="transfucionesCheck">Requiere Transfusiones</label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-medium text-secondary small">Otras Indicaciones Médicas</label>
                        <textarea name="otras_indicaciones" rows="3" class="form-control">{{ old('otras_indicaciones', $medical->otras_indicaciones) }}</textarea>
                    </div>
                </fieldset>

                <!-- Temperaturas y Presiones -->
                <fieldset class="border rounded p-3 mb-4">
                    <legend class="float-none w-auto px-2 fs-6 fw-bold text-primary">Parámetros Máquina y Monitor</legend>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Temperatura Inicial</label>
                            <input type="text" name="t_inicial" value="{{ old('t_inicial', $medical->t_inicial) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Temperatura Final</label>
                            <input type="text" name="t_final" value="{{ old('t_final', $medical->t_final) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Presión Inicial</label>
                            <input type="text" name="p_inicial" value="{{ old('p_inicial', $medical->p_inicial) }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-medium text-secondary small">Presión Final</label>
                            <input type="text" name="p_final" value="{{ old('p_final', $medical->p_final) }}" class="form-control">
                        </div>
                    </div>
                </fieldset>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('medicals.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary" style="background-color: var(--hc-primary); border: none;">
                        Guardar Registro Médico
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection