@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px;">
    <div class="mb-3">
        <a href="{{ route('nurses.index') }}" class="text-decoration-none text-muted small">
            <i class="fa-solid fa-chevron-left me-1"></i> Volver a la Orden
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-user-nurse text-primary me-2"></i>Modificar Evolución Metodológica (SOAPIE)</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('nurses.update', $nurse->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12 border-start border-warning border-3 ps-3">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-warning text-dark fs-6">S</span>
                            <label class="form-label fw-bold mb-0 text-dark">Datos Subjetivos (Lo que refiere el paciente)</label>
                            <input type="time" class="form-control form-control-sm ms-auto" name="hora1" value="{{ old('hora1', \Carbon\Carbon::parse($nurse->hora1)->format('H:i')) }}" style="max-width: 110px;" required>
                        </div>
                        <textarea class="form-control" name="s_subjetivo" rows="2" required>{{ old('s_subjetivo', $nurse->s_subjetivo) }}</textarea>
                    </div>

                    <div class="col-12 border-start border-info border-3 ps-3">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-info text-white fs-6">O</span>
                            <label class="form-label fw-bold mb-0 text-dark">Datos Objetivos (Examen físico y monitores)</label>
                            <input type="time" class="form-control form-control-sm ms-auto" name="hora2" value="{{ old('hora2', \Carbon\Carbon::parse($nurse->hora2)->format('H:i')) }}" style="max-width: 110px;" required>
                        </div>
                        <textarea class="form-control" name="o_objetivo" rows="2" required>{{ old('o_objetivo', $nurse->o_objetivo) }}</textarea>
                    </div>

                    <div class="col-12 border-start border-danger border-3 ps-3">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-danger text-white fs-6">A</span>
                            <label class="form-label fw-bold mb-0 text-dark">Análisis (Diagnóstico de enfermería NANDA)</label>
                            <input type="time" class="form-control form-control-sm ms-auto" name="hora3" value="{{ old('hora3', \Carbon\Carbon::parse($nurse->hora3)->format('H:i')) }}" style="max-width: 110px;" required>
                        </div>
                        <textarea class="form-control" name="a_analisis" rows="2" required>{{ old('a_analisis', $nurse->a_analisis) }}</textarea>
                    </div>

                    <div class="col-12 border-start border-primary border-3 ps-3">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-primary text-white fs-6">P</span>
                            <label class="form-label fw-bold mb-0 text-dark">Planificación (Objetivos inmediatos NOC)</label>
                            <input type="time" class="form-control form-control-sm ms-auto" name="hora4" value="{{ old('hora4', \Carbon\Carbon::parse($nurse->hora4)->format('H:i')) }}" style="max-width: 110px;" required>
                        </div>
                        <textarea class="form-control" name="p_planificacion" rows="2" required>{{ old('p_planificacion', $nurse->p_planificacion) }}</textarea>
                    </div>

                    <div class="col-12 border-start border-success border-3 ps-3">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-success text-white fs-6">I</span>
                            <label class="form-label fw-bold mb-0 text-dark">Intervención (Ejecución de los cuidados NIC)</label>
                            <input type="time" class="form-control form-control-sm ms-auto" name="hora5" value="{{ old('hora5', \Carbon\Carbon::parse($nurse->hora5)->format('H:i')) }}" style="max-width: 110px;" required>
                        </div>
                        <textarea class="form-control" name="i_intervencion" rows="2" required>{{ old('i_intervencion', $nurse->i_intervencion) }}</textarea>
                    </div>

                    <div class="col-12 border-start border-secondary border-3 ps-3">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-secondary text-white fs-6">E</span>
                            <label class="form-label fw-bold mb-0 text-dark">Evaluación (Resultados finales obtenidos)</label>
                            <input type="time" class="form-control form-control-sm ms-auto" name="hora6" value="{{ old('hora6', \Carbon\Carbon::parse($nurse->hora6)->format('H:i')) }}" style="max-width: 110px;" required>
                        </div>
                        <textarea class="form-control" name="e_evaluacion" rows="2" required>{{ old('e_evaluacion', $nurse->e_evaluacion) }}</textarea>
                    </div>
                </div>

                <div class="row g-3 mt-3 border-top pt-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ultrafiltración Efectiva Total (UF)</label>
                        <input type="text" class="form-control" name="uf_efectivo" value="{{ old('uf_efectivo', $nurse->uf_efectivo) }}" placeholder="Ej: 2500 ml">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Aspecto del Filtro / Observaciones</label>
                        <input type="text" class="form-control" name="asp_filtro" value="{{ old('asp_filtro', $nurse->asp_filtro) }}" placeholder="Limpio, coágulos leves...">
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Eritropoyetina (EPO)</label>
                        <input type="text" class="form-control" name="epo" value="{{ old('epo', $nurse->epo) }}" placeholder="Dosis...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Hierro</label>
                        <input type="text" class="form-control" name="hierro" value="{{ old('hierro', $nurse->hierro) }}" placeholder="Dosis...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Vitamina B12</label>
                        <input type="text" class="form-control" name="vitb12" value="{{ old('vitb12', $nurse->vitb12) }}" placeholder="Dosis...">
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color: var(--hc-primary); border: none;">
                        <i class="fa-solid fa-arrows-rotate me-2"></i>Actualizar Registro SOAPIE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection