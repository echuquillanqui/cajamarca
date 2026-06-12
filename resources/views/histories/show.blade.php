@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="text-uppercase text-primary small fw-bold tracking-wider">Historial Clínico Consolidado</span>
                <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-id-card text-secondary me-2"></i>Expediente de {{ $history->patient?->nombre ?? 'Paciente no disponible' }}</h4>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-light border px-3 rounded-3 small me-2">
                    <i class="fa-solid fa-print me-1"></i> Imprimir Ficha
                </button>
                <a href="{{ route('histories.index') }}" class="btn btn-primary px-4 rounded-3 small fw-bold shadow-sm">
                    Regresar al Control
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-dark"><i class="fa-solid fa-circle-info me-2 text-primary"></i>Métricas de Admisión</div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <span class="text-muted d-block small">Fecha Apertura HD:</span>
                        <div class="fw-bold text-dark fs-5"><i class="fa-regular fa-calendar-check text-muted me-2"></i>{{ \Carbon\Carbon::parse($history->fecha_ingreso_hd)->format('d/m/Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted d-block small">Servicio de Origen:</span>
                        <span class="badge bg-light text-dark border px-3 py-1 mt-1">{{ $history->serv_origen ?? 'Sin especificar' }}</span>
                    </div>
                    <hr>
                    <h6 class="fw-bold text-dark mb-3">Funciones Vitales Registradas</h6>
                    <table class="table table-sm table-borderless small mb-0">
                        <tr><td class="text-secondary ps-0">Presión Arterial:</td><td class="fw-bold text-dark text-end"><code>{{ $history->pa ?? 'N/A' }}</code> mmHg</td></tr>
                        <tr><td class="text-secondary ps-0">Frecuencia Cardíaca:</td><td class="fw-bold text-dark text-end">{{ $history->fc ?? 'N/A' }} Lpm</td></tr>
                        <tr><td class="text-secondary ps-0">Saturación O₂:</td><td class="fw-bold text-dark text-end">{{ $history->sat_o2 ?? 'N/A' }}%</td></tr>
                        <tr><td class="text-secondary ps-0">Peso Ingreso:</td><td class="fw-bold text-dark text-end">{{ $history->peso_ingreso ?? 'N/A' }} Kg</td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-dark"><i class="fa-solid fa-shield-virus me-2 text-danger"></i>Seguridad Biológica (Serología)</div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-2">
                        @foreach(['hiv' => 'Tamizaje VIH', 'hbsag' => 'Ag Superficie Hep B (HBsAg)', 'anti_hbc' => 'Anti-HBc', 'vhc' => 'Hepatitis C (VHC)', 'anti_hbs' => 'Anti-HBs', 'rpr' => 'RPR / Sífilis'] as $field => $label)
                            <div class="d-flex justify-content-between p-2 rounded border {{ $history->{$field} ? 'bg-danger bg-opacity-10 text-danger' : 'bg-light text-dark' }}">
                                <span class="small fw-semibold">{{ $label }}:</span><span class="small fw-bold">{{ $history->{$field} ? 'REACTIVO (+)' : 'NO REACTIVO (-)' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white fw-bold text-dark"><i class="fa-solid fa-code-fork me-2 text-primary"></i>Accesos y Terapias Previas</div>
                <div class="card-body p-4 small">
                    <div class="row g-2">
                        <div class="col-md-6"><strong>Acceso principal:</strong> {{ $history->tipo ?? 'N/A' }} / {{ $history->localizacion ?? 'N/A' }} / {{ $history->lado ?? 'N/A' }}</div>
                        <div class="col-md-6"><strong>Estado:</strong> {{ $history->estado ?? 'N/A' }}</div>
                        <div class="col-md-6"><strong>Acceso secundario:</strong> {{ $history->tipo2 ?? 'N/A' }} / {{ $history->localizacion2 ?? 'N/A' }} / {{ $history->lado2 ?? 'N/A' }}</div>
                        <div class="col-md-6"><strong>Biopsia renal:</strong> {{ $history->biopsia_renal ? 'SÍ' : 'NO' }} {{ $history->biopsia_renal_anio ? '('.$history->biopsia_renal_anio.')' : '' }}</div>
                        <div class="col-md-12"><strong>Resultado biopsia:</strong> {{ $history->biopsia_renal_resultado ?? 'Sin registro' }}</div>
                        <div class="col-md-6"><strong>Diálisis peritoneal previa:</strong> {{ $history->d_peritoneal ? 'SÍ' : 'NO' }}</div>
                        <div class="col-md-6"><strong>Trasplante renal previo:</strong> {{ $history->t_renal ? 'SÍ' : 'NO' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-bold text-dark"><i class="fa-solid fa-book-open-reader me-2 text-primary"></i>Relato Cronológico y Antecedentes</div>
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-secondary small fw-bold mb-1">Evolución de la Enfermedad</h6>
                    <p class="text-dark bg-light p-3 rounded-3" style="text-align: justify; line-height: 1.5; font-size: 0.95rem;">
                        {{ $history->relato_cronologico ?? 'No se digitó relato cronológico en la apertura del historial médico.' }}
                    </p>

                    <h6 class="text-uppercase text-secondary small fw-bold mt-4 mb-2">Antecedentes Personales Persistidos (JSON Decoded)</h6>
                    @if($history->antecedentes_personales)
                        @php $ant = json_decode(json_encode($history->antecedentes_personales)); @endphp
                        <div class="row g-2 bg-light p-3 rounded-3 small">
                            <div class="col-md-6"><strong>¿Diabetes Mellitus?:</strong> {{ isset($ant->diabetes) && $ant->diabetes ? 'SÍ' : 'NO' }}</div>
                            <div class="col-md-6"><strong>¿Hipertensión Arterial?:</strong> {{ isset($ant->hta) && $ant->hta ? 'SÍ' : 'NO' }}</div>
                            <div class="col-md-12 mt-2 border-top pt-2"><strong>Medicación Recibida:</strong> {{ $ant->medicacion_previa ?? 'Ninguna especificada' }}</div>
                        </div>
                    @else
                        <span class="text-muted small">Sin datos registrados.</span>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-dark"><i class="fa-solid fa-bacteria me-2 text-primary"></i>Juicio Diagnóstico Actualizado (CIE-10 Matriz)</div>
                <div class="card-body p-4">
                    @if($history->diagnostico && is_array($history->diagnostico))
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0 small">
                                <thead class="table-light">
                                    <tr><th style="width: 25%;">Código CIE-10</th><th>Descripción del Diagnóstico Médico</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($history->diagnostico as $diag)
                                    <tr>
                                        <td><code class="text-primary fw-bold">{{ $diag['cie10'] ?? 'N/A' }}</code></td>
                                        <td class="text-dark fw-medium">{{ $diag['descripcion'] ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted small bg-light p-3 rounded-3">No se indexaron sub-códigos de comorbilidades.</div>
                    @endif

                    <div class="row mt-4 pt-3 border-top g-3">
                        <div class="col-md-6">
                            <span class="small fw-bold text-secondary d-block mb-1">Pendientes de Monitoreo:</span>
                            <div class="p-2 border rounded bg-light text-dark small" style="white-space: pre-line;">{{ $history->pendientes ?? 'Ninguno' }}</div>
                        </div>
                        <div class="col-md-6">
                            <span class="small fw-bold text-secondary d-block mb-1">Médico que Apertura:</span>
                            <div class="p-2 border rounded bg-light text-dark small"><i class="fa-solid fa-user-md me-2 text-muted"></i>{{ $history->user?->name ?? 'Usuario no disponible' }}</div>
                        </div>
                        <div class="col-md-6">
                            <span class="small fw-bold text-secondary d-block mb-1">Consideraciones de alta:</span>
                            <div class="p-2 border rounded bg-light text-dark small" style="white-space: pre-line;">{{ $history->consideraciones_alta ?? 'Ninguna' }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="small fw-bold text-secondary d-block mb-1">Fecha alta:</span>
                            <div class="p-2 border rounded bg-light text-dark small">{{ $history->f_alta ? $history->f_alta->format('d/m/Y') : 'Sin alta' }}</div>
                        </div>
                        <div class="col-md-3">
                            <span class="small fw-bold text-secondary d-block mb-1">Peso seco:</span>
                            <div class="p-2 border rounded bg-light text-dark small">{{ $history->peso_seco ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection