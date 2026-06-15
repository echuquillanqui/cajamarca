@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3" x-data="treatmentManager()">
    
    <div class="mb-3">
        <a href="{{ route('treatments.index') }}" class="text-decoration-none text-secondary small fw-semibold">
            <i class="fa-solid fa-chevron-left me-1"></i> Cancelar y Volver al Expediente
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        
        <div class="card-header bg-dark py-3 px-4 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-1 text-white fw-bold">
                    <i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Monitoreo de Parámetros Horarios (Hemodiálisis)
                </h5>
                <p class="text-muted small mb-0">Registre y controle secuencialmente la evolución horaria del paciente durante la sesión.</p>
            </div>
            <div>
                <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold">Máx. 8 Horas</span>
            </div>
        </div>

        <div class="card-body p-4 bg-light-subtle">
            <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 py-2 px-3">
                <i class="fa-solid fa-circle-info text-info fs-5 me-2"></i>
                <span class="small text-dark-emphasis">Puede agregar hasta 8 controles secuenciales. Use el botón <strong class="text-primary">"Agregar Siguiente Hora"</strong> para abrir una nueva fila.</span>
            </div>

            <form action="{{ route('treatments.update', $order_id ?? $treatment->order_id ?? 1) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive rounded-3 border bg-white shadow-sm mb-3">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-secondary small fw-bold text-uppercase border-bottom">
                            <tr>
                                <th style="width: 105px;" class="py-3">Hora <span class="text-danger">*</span></th>
                                <th style="width: 110px;">P.A. (mmHg)</th>
                                <th style="width: 80px;">PAM</th>
                                <th style="width: 80px;">F.C. (lpm)</th>
                                <th style="width: 80px;">SatO₂ (%)</th>
                                <th style="width: 85px;">UF Hora</th>
                                <th style="width: 80px;">Sodio</th>
                                <th style="width: 85px;">QB</th>
                                <th style="width: 80px;">P.A. (RA)</th>
                                <th style="width: 80px;">P.V. (RV)</th>
                                <th style="width: 85px;">PTM <span class="text-danger">*</span></th>
                                <th>Laboratorio Control / Observaciones</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(row, index) in rows" :key="index">
                                <tr class="transition-row">
                                    <input type="hidden" :name="'treatments['+index+'][id]'" x-model="row.id">

                                    <td class="p-1">
                                        <input type="time" class="form-control form-control-sm font-monospace text-center fw-bold border-secondary-subtle" 
                                               :name="'treatments['+index+'][hora]'" x-model="row.hora" required>
                                    </td>
                                    <td class="p-1">
                                        <input type="text" class="form-control form-control-sm text-center font-monospace" 
                                               :name="'treatments['+index+'][pa]'" x-model="row.pa" placeholder="120/80">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace bg-light border-0" 
                                               :name="'treatments['+index+'][pam]'" x-model="row.pam" placeholder="—">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace" 
                                               :name="'treatments['+index+'][fc]'" x-model="row.fc" placeholder="—">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace" 
                                               :name="'treatments['+index+'][sao2]'" x-model="row.sao2" placeholder="—">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace" 
                                               :name="'treatments['+index+'][uf_hora]'" x-model="row.uf_hora" placeholder="0">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace" 
                                               :name="'treatments['+index+'][sodio]'" x-model="row.sodio" placeholder="—">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace fw-semibold text-primary" 
                                               :name="'treatments['+index+'][qb]'" x-model="row.qb" placeholder="300">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace text-danger" 
                                               :name="'treatments['+index+'][ra]'" x-model="row.ra" placeholder="—">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace text-success" 
                                               :name="'treatments['+index+'][rv]'" x-model="row.rv" placeholder="—">
                                    </td>
                                    <td class="p-1">
                                        <input type="number" class="form-control form-control-sm text-center font-monospace border-danger-subtle fw-bold" 
                                               :name="'treatments['+index+'][ptm]'" x-model="row.ptm" placeholder="0" required>
                                    </td>
                                    <td class="p-1">
                                        <div class="d-flex gap-1">
                                            <input type="text" class="form-control form-control-sm w-50" :name="'treatments['+index+'][laboratorio_control]'" x-model="row.laboratorio_control" placeholder="Lab Control">
                                            <input type="text" class="form-control form-control-sm w-50" :name="'treatments['+index+'][observaciones]'" x-model="row.observaciones" placeholder="Observaciones">
                                        </div>
                                    </td>
                                    <td class="p-1 text-center">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0 border-0" @click="removeRow(index)" title="Eliminar">
                                            <i class="fa-solid fa-trash-can fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-start gap-2 mb-4">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-3 fw-bold shadow-sm" @click="addRow()" :disabled="rows.length >= 8">
                        <i class="fa-solid fa-plus me-1"></i> Agregar Siguiente Hora
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold shadow-sm" @click="cloneLastRow()" :disabled="rows.length == 0 || rows.length >= 8">
                        <i class="fa-solid fa-clone me-1"></i> Clonar Últimos Parámetros
                    </button>
                </div>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small italic">
                        Filas activas: <span x-text="rows.length" class="fw-bold text-dark"></span> de 8 máximas.
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('treatments.edit', $treatment->order_id  ?? $treatment->order_id ?? 1) }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm" style="background-color: var(--hc-primary); border: none;">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Monitoreo Masivo
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function treatmentManager() {
        return {
            // Inicializa con registros existentes de la base de datos o una fila en blanco
            rows: @json($treatments_json ?? []),

            init() {
                if (this.rows.length === 0) {
                    this.addRow();
                }
            },

            addRow() {
                if (this.rows.length < 8) {
                    let nextHour = '08:00';
                    if (this.rows.length > 0) {
                        let lastHourStr = this.rows[this.rows.length - 1].hora;
                        if (lastHourStr) {
                            let parts = lastHourStr.split(':');
                            let h = (parseInt(parts[0]) + 1) % 24;
                            nextHour = (h < 10 ? '0' : '') + h + ':' + parts[1];
                        }
                    }

                    this.rows.push({
                        id: null,
                        hora: nextHour,
                        pa: '',
                        pam: '',
                        fc: '',
                        sao2: '',
                        uf_hora: '',
                        sodio: '',
                        qb: '300',
                        ra: '',
                        rv: '',
                        ptm: '0',
                        laboratorio_control: '',
                        observaciones: ''
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Límite alcanzado',
                        text: 'No se pueden registrar más de 8 horas por sesión.',
                        customClass: { popup: 'rounded-4' }
                    });
                }
            },

            cloneLastRow() {
                if (this.rows.length > 0 && this.rows.length < 8) {
                    let source = this.rows[this.rows.length - 1];
                    let parts = source.hora.split(':');
                    let h = (parseInt(parts[0]) + 1) % 24;
                    let nextHour = (h < 10 ? '0' : '') + h + ':' + parts[1];

                    this.rows.push({
                        id: null,
                        hora: nextHour,
                        pa: source.pa,
                        pam: source.pam,
                        fc: source.fc,
                        sao2: source.sao2,
                        uf_hora: source.uf_hora,
                        sodio: source.sodio,
                        qb: source.qb,
                        ra: source.ra,
                        rv: source.rv,
                        ptm: source.ptm,
                        laboratorio_control: '',
                        observaciones: ''
                    });
                }
            },

            removeRow(index) {
                if (this.rows.length === 1) {
                    return;
                }
                this.rows.splice(index, 1);
            }
        }
    }
</script>

<style>
    /* Estilos de alta densidad clínica para resoluciones horizontales */
    .table th {
        font-size: 0.72rem !important;
        letter-spacing: 0.3px;
        padding: 10px 4px !important;
    }
    .form-control-sm {
        font-size: 0.8rem;
        padding: 0.2rem 0.3rem;
        border-radius: 4px;
    }
    .transition-row {
        transition: background-color 0.2s ease;
    }
    .transition-row:hover {
        background-color: rgba(10, 102, 194, 0.03) !important;
    }
</style>
@endpush