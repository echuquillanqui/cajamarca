@extends('layouts.app')

@section('content')
<div class="container px-4" x-data="patientModule()">
    
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-hospital-user text-primary me-2"></i>Control de Pacientes Admisión
                </h4>
                <p class="text-muted small mb-0">Módulo de registro, aseguramiento (SIS/EsSalud) y contactos de emergencia para hemodiálisis.</p>
            </div>
            <button type="button" class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm" @click="openCreate()">
                <i class="fa-solid fa-plus me-2"></i>Admitir Nuevo Paciente
            </button>
        </div>
    </div>

    <div class="row mb-3 align-items-center justify-content-between g-3">
        <div class="col-md-4">
            <div class="input-group shadow-sm rounded-3">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" x-model="search" class="form-control border-start-0 ps-0" placeholder="Buscar por Nombre, DNI o Teléfono...">
            </div>
        </div>
        <div class="col-md-auto d-flex align-items-center gap-2">
            <span class="text-muted small">Mostrar:</span>
            <select x-model="perPage" class="form-select form-select-sm rounded-2 bg-white shadow-sm" style="width: 80px;" @change="page = 1">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
            </select>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light text-secondary small fw-bold text-uppercase">
                    <tr>
                        <th class="ps-4">Paciente / Documentos</th>
                        <th>Contacto</th>
                        <th>Aseguramiento</th>
                        <th>Procedencia / Dirección</th>
                        <th>Contacto Emergencia</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="patient in paginatedPatients()" :key="patient.id">
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark" x-text="patient.nombre"></div>
                                <div class="text-muted small">
                                    <span class="badge bg-light text-dark border me-1">DNI: <span x-text="patient.dni"></span></span>
                                    <span class="badge bg-light text-dark border"><span x-text="patient.sexo === 'M' ? 'Masculino' : 'Femenino'"></span></span>
                                </div>
                            </td>
                            <td>
                                <div class="small text-dark"><i class="fa-solid fa-phone text-muted me-1 small"></i><span x-text="patient.telefono || '---'"></span></div>
                                <div class="text-muted small opacity-70" x-text="'Nac: ' + formatDate(patient.fecha_nacimiento)"></div>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success rounded px-2" x-text="patient.financiador || 'Particular'"></span>
                                <small class="text-muted d-block mt-1 small" x-text="patient.codigo_seguro ? 'Cód: ' + patient.codigo_seguro : ''"></small>
                            </td>
                            <td>
                                <div class="small text-truncate" style="max-width: 180px;" x-text="patient.direccion || '---'"></div>
                                <small class="text-muted small d-block" x-text="patient.procedencia || ''"></small>
                                <small class="text-muted small d-block" x-text="patientUbigeo(patient)"></small>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark" x-text="patient.contacto_emergencia_nombre ? patient.contacto_emergencia_nombre : '---'"></div>
                                <small class="text-muted small block" x-text="patient.contacto_emergencia_telefono ? 'Tlf: ' + patient.contacto_emergencia_telefono : ''"></small>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-light border text-info" title="Editar Ficha" @click="openEdit(patient)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-light border text-danger" title="Eliminar del Sistema" 
                                            @click="deletePatient(patient.id, patient.nombre)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredPatients().length === 0">
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fa-solid fa-folder-open fs-3 d-block mb-2 opacity-50"></i>
                            No se encontraron pacientes que coincidan con los criterios de búsqueda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2" x-show="totalPages() > 1">
            <span class="small text-muted" x-text="`Mostrando registros del ${(page - 1) * perPage + 1} al ${Math.min(page * perPage, filteredPatients().length)} de ${filteredPatients().length}`"></span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item" :class="page === 1 ? 'disabled' : ''">
                        <button class="page-link" @click="page--"><i class="fa-solid fa-angle-left"></i></button>
                    </li>
                    <template x-for="p in totalPages()" :key="p">
                        <li class="page-item" :class="page === p ? 'active' : ''">
                            <button class="page-link" x-text="p" @click="page = p"></button>
                        </li>
                    </template>
                    <li class="page-item" :class="page === totalPages() ? 'disabled' : ''">
                        <button class="page-link" @click="page++"><i class="fa-solid fa-angle-right"></i></button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="modal fade" id="crudPatientModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg" x-data="{ modalTab: 'personal' }">
                
                <div class="modal-header border-bottom py-3" :class="isEdit ? 'bg-info bg-opacity-10' : 'bg-primary bg-opacity-10'">
                    <h5 class="modal-title fw-bold" :class="isEdit ? 'text-info' : 'text-primary'">
                        <i class="fa-solid text-opacity-70 me-2" :class="isEdit ? 'fa-user-pen' : 'fa-user-plus'"></i>
                        <span x-text="isEdit ? 'Modificar Ficha de Paciente' : 'Aperturar Ficha de Admisión'"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form :action="isEdit ? `{{ url('patients') }}/${form.id}` : '{{ route('patients.store') }}'" method="POST" autocomplete="off">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="modal-body p-4">
                        
                        <ul class="nav nav-tabs mb-4 border-bottom-0 sub-tabs" id="modalPatientTabs">
                            <li class="nav-item">
                                <button type="button" class="nav-link py-2 px-3 small rounded-3 border-0 me-2" :class="modalTab === 'personal' ? 'bg-secondary bg-opacity-10 text-dark fw-bold active' : 'text-muted'" @click="modalTab = 'personal'">1. Datos Personales</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link py-2 px-3 small rounded-3 border-0 me-2" :class="modalTab === 'seguro' ? 'bg-secondary bg-opacity-10 text-dark fw-bold active' : 'text-muted'" @click="modalTab = 'seguro'">2. Aseguramiento</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link py-2 px-3 small rounded-3 border-0" :class="modalTab === 'contacto' ? 'bg-secondary bg-opacity-10 text-dark fw-bold active' : 'text-muted'" @click="modalTab = 'contacto'">3. Emergencia</button>
                            </li>
                        </ul>

                        <div x-show="modalTab === 'personal'" class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Apellidos y Nombres Completos *</label>
                                <input type="text" name="nombre" x-model="form.nombre" class="form-control rounded-3" placeholder="Ej: Pérez Quispe, Juan Carlos" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">N° Documento (DNI/CE) *</label>
                                <input type="text" name="dni" x-model="form.dni" class="form-control rounded-3" maxlength="15" placeholder="Documento de identidad" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Fecha Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" x-model="form.fecha_nacimiento" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Sexo Biológico *</label>
                                <select name="sexo" x-model="form.sexo" class="form-select rounded-3" required>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Teléfono Particular</label>
                                <input type="text" name="telefono" x-model="form.telefono" class="form-control rounded-3" placeholder="Ej: 987654321">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Procedencia</label>
                                <input type="text" name="procedencia" x-model="form.procedencia" class="form-control rounded-3" placeholder="Ej: Centro poblado o sector">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Departamento</label>
                                <select id="patient_id_departamento" name="id_departamento" x-model="form.id_departamento" class="form-select rounded-3 select2-ubigeo" data-placeholder="Seleccione departamento" @change="onDepartamentoChange()">
                                    <option value=""></option>
                                    <template x-for="departamento in departamentos" :key="departamento.id_departamento">
                                        <option :value="departamento.id_departamento" x-text="departamento.descripcion"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Provincia</label>
                                <select id="patient_id_provincia" name="id_provincia" x-model="form.id_provincia" class="form-select rounded-3 select2-ubigeo" data-placeholder="Seleccione provincia" @change="onProvinciaChange()">
                                    <option value=""></option>
                                    <template x-for="provincia in filteredProvincias()" :key="`${provincia.id_departamento}-${provincia.id_provincia}`">
                                        <option :value="provincia.id_provincia" x-text="provincia.descripcion"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Distrito</label>
                                <select id="patient_id_distrito" name="id_distrito" x-model="form.id_distrito" class="form-select rounded-3 select2-ubigeo" data-placeholder="Seleccione distrito">
                                    <option value=""></option>
                                    <template x-for="distrito in filteredDistritos()" :key="`${distrito.id_departamento}-${distrito.id_provincia}-${distrito.id_distrito}`">
                                        <option :value="distrito.id_distrito" x-text="distrito.descripcion"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Grado Instrucción</label>
                                <select name="instruccion" x-model="form.instruccion" class="form-select rounded-3">
                                    <option value="">Seleccione grado de instrucción</option>
                                    <option value="Sin instrucción">Sin instrucción</option>
                                    <option value="Inicial">Inicial</option>
                                    <option value="Primaria Incompleta">Primaria Incompleta</option>
                                    <option value="Primaria Completa">Primaria Completa</option>
                                    <option value="Secundaria Incompleta">Secundaria Incompleta</option>
                                    <option value="Secundaria Completa">Secundaria Completa</option>
                                    <option value="Técnica Incompleta">Técnica Incompleta</option>
                                    <option value="Técnica Completa">Técnica Completa</option>
                                    <option value="Superior Incompleta">Superior Incompleta</option>
                                    <option value="Superior Completa">Superior Completa</option>
                                    <option value="Postgrado">Postgrado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Estado Civil</label>
                                <select name="civil" x-model="form.civil" class="form-select rounded-3">
                                    <option value="">Seleccione estado civil</option>
                                    <option value="Soltero(a)">Soltero(a)</option>
                                    <option value="Casado(a)">Casado(a)</option>
                                    <option value="Conviviente">Conviviente</option>
                                    <option value="Divorciado(a)">Divorciado(a)</option>
                                    <option value="Separado(a)">Separado(a)</option>
                                    <option value="Viudo(a)">Viudo(a)</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-secondary">Dirección de Domicilio Actual</label>
                                <input type="text" name="direccion" x-model="form.direccion" class="form-control rounded-3" placeholder="Av. Centenario N° 450">
                            </div>
                        </div>

                        <div x-show="modalTab === 'seguro'" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Financiador / Tipo Seguro</label>
                                <select name="financiador" x-model="form.financiador" class="form-select rounded-3">
                                    <option value="SIS">SIS (Seguro Integral de Salud)</option>
                                    <option value="EsSalud">EsSalud</option>
                                    <option value="Particular">Particular / Auto-Financiado</option>
                                    <option value="Otros">Otros Seguros / EPS</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Código Afiliación / Póliza</label>
                                <input type="text" name="codigo_seguro" x-model="form.codigo_seguro" class="form-control rounded-3" placeholder="Ej: 2-04571822">
                            </div>
                        </div>

                        <div x-show="modalTab === 'contacto'" class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-secondary">Nombre de Contacto de Emergencia</label>
                                <input type="text" name="contacto_emergencia_nombre" x-model="form.contacto_emergencia_nombre" class="form-control rounded-3" placeholder="Familiar directo o apoderado">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-secondary">Parentesco / Vínculo</label>
                                <input type="text" name="contacto_emergencia_parentesco" x-model="form.contacto_emergencia_parentesco" class="form-control rounded-3" placeholder="Ej: Cónyuge, Hijo, Hermano">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">DNI Contacto</label>
                                <input type="text" name="contacto_emergencia_dni" x-model="form.contacto_emergencia_dni" class="form-control rounded-3" maxlength="15" placeholder="N° Documento">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Teléfono Urgencias</label>
                                <input type="text" name="contacto_emergencia_telefono" x-model="form.contacto_emergencia_telefono" class="form-control rounded-3" placeholder="Disponible las 24 hrs">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light border-top py-3 rounded-bottom-4">
                        <button type="button" class="btn btn-light border px-4 rounded-3 text-secondary small" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn px-4 rounded-3 fw-bold shadow-sm" :class="isEdit ? 'btn-info text-white' : 'btn-primary'">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i>
                            <span x-text="isEdit ? 'Guardar Cambios' : 'Registrar Admisión'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="hidden-delete-patient-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('patientModule', () => ({
            search: '',
            page: 1,
            perPage: 10,
            patientsList: @json($patients ?? []),
            departamentos: @json($departamentos ?? []),
            provincias: @json($provincias ?? []),
            distritos: @json($distritos ?? []),

            isEdit: false,
            modalInstance: null,
            form: {
                id: '', nombre: '', dni: '', fecha_nacimiento: '', sexo: 'M', telefono: '',
                procedencia: '', id_departamento: '', id_provincia: '', id_distrito: '', direccion: '', instruccion: '', civil: '',
                financiador: 'SIS', codigo_seguro: '',
                contacto_emergencia_nombre: '', contacto_emergencia_dni: '',
                contacto_emergencia_parentesco: '', contacto_emergencia_telefono: ''
            },

            init() {
                const modalEl = document.getElementById('crudPatientModal');
                if (modalEl && typeof bootstrap !== 'undefined') {
                    this.modalInstance = new bootstrap.Modal(modalEl);
                }
            },

            filteredProvincias() {
                return this.provincias.filter(provincia => provincia.id_departamento === this.form.id_departamento);
            },

            filteredDistritos() {
                return this.distritos.filter(distrito => {
                    return distrito.id_departamento === this.form.id_departamento &&
                           distrito.id_provincia === this.form.id_provincia;
                });
            },

            onDepartamentoChange() {
                this.form.id_provincia = '';
                this.form.id_distrito = '';
                this.refreshSelect2Values();
            },

            onProvinciaChange() {
                this.form.id_distrito = '';
                this.refreshSelect2Values();
            },

            patientUbigeo(patient) {
                const parts = [patient.departamento?.descripcion, patient.provincia?.descripcion, patient.distrito?.descripcion].filter(Boolean);
                return parts.length ? parts.join(' / ') : '';
            },

            initSelect2() {
                if (typeof $ === 'undefined' || !$.fn.select2) return;

                this.$nextTick(() => {
                    $('.select2-ubigeo').each((_, element) => {
                        const $element = $(element);
                        if (!$element.data('select2')) {
                            $element.select2({
                                theme: 'bootstrap-5',
                                dropdownParent: $('#crudPatientModal'),
                                width: '100%',
                                allowClear: true,
                                placeholder: $element.data('placeholder') || 'Seleccione una opción'
                            }).on('change', () => {
                                this.form[element.name] = $element.val() || '';

                                if (element.name === 'id_departamento') {
                                    this.onDepartamentoChange();
                                }

                                if (element.name === 'id_provincia') {
                                    this.onProvinciaChange();
                                }
                            });
                        }

                        $element.val(this.form[element.name] || '').trigger('change.select2');
                    });
                });
            },

            refreshSelect2Values() {
                this.$nextTick(() => {
                    if (typeof $ === 'undefined' || !$.fn.select2) return;
                    $('#patient_id_departamento').val(this.form.id_departamento || '').trigger('change.select2');
                    $('#patient_id_provincia').val(this.form.id_provincia || '').trigger('change.select2');
                    $('#patient_id_distrito').val(this.form.id_distrito || '').trigger('change.select2');
                });
            },

            filteredPatients() {
                if (!this.search || this.search.trim() === '') {
                    return this.patientsList;
                }
                let query = this.search.toLowerCase().trim();
                return this.patientsList.filter(p => {
                    return (p.nombre && p.nombre.toLowerCase().includes(query)) ||
                           (p.dni && String(p.dni).includes(query)) ||
                           (p.telefono && String(p.telefono).includes(query));
                });
            },

            paginatedPatients() {
                let start = (this.page - 1) * this.perPage;
                let end = start + this.perPage;
                return this.filteredPatients().slice(start, end);
            },

            totalPages() {
                return Math.ceil(this.filteredPatients().length / this.perPage);
            },

            openCreate() {
                this.isEdit = false;
                this.form = {
                    id: '', nombre: '', dni: '', fecha_nacimiento: '', sexo: 'M', telefono: '',
                    procedencia: '', id_departamento: '', id_provincia: '', id_distrito: '', direccion: '', instruccion: '', civil: '',
                    financiador: 'SIS', codigo_seguro: '',
                    contacto_emergencia_nombre: '', contacto_emergencia_dni: '',
                    contacto_emergencia_parentesco: '', contacto_emergencia_telefono: ''
                };
                if (this.modalInstance) this.modalInstance.show();
                this.initSelect2();
            },

            openEdit(patient) {
                this.isEdit = true;
                this.form = Object.assign({}, patient, {
                    fecha_nacimiento: this.normalizeDate(patient.fecha_nacimiento)
                });
                if (this.modalInstance) this.modalInstance.show();
                this.initSelect2();
            },

            deletePatient(id, name) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¿Retirar expediente?',
                        text: `El paciente "${name}" será removido de los flujos activos de la unidad de hemodiálisis.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e63946',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, remover',
                        cancelButtonText: 'Conservar activo',
                        background: '#ffffff',
                        customClass: { popup: 'rounded-4' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let form = document.getElementById('hidden-delete-patient-form');
                            form.action = `{{ url('patients') }}/${id}`;
                            form.submit();
                        }
                    });
                }
            },

            normalizeDate(dateString) {
                if (!dateString) return '';
                return String(dateString).split('T')[0];
            },

            formatDate(dateString) {
                const normalizedDate = this.normalizeDate(dateString);
                if (!normalizedDate) return '';
                const parts = normalizedDate.split('-');
                if (parts.length === 3) {
                    return `${parts[2]}/${parts[1]}/${parts[0]}`;
                }
                return dateString;
            }
        }));
    });
</script>
@endpush