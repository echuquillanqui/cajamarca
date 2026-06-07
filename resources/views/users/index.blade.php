@extends('layouts.app')

@section('content')
<div class="container-fluid px-4" x-data="userModule">
    
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", "Error de Validación de Datos");
                @endforeach
            });
        </script>
    @endif

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">
                    <i class="fa-solid fa-id-card-clip text-primary me-2"></i>Control de Personal y Accesos
                </h4>

            </div>
            <button class="btn btn-primary px-4 rounded-3 fw-bold shadow-sm" @click="openCreate()">
                <i class="fa-solid fa-user-plus me-2"></i>Registrar Nuevo Personal
            </button>
        </div>
    </div>

    <div class="row mb-3 g-3 align-items-center">
        <div class="col-md-5 col-lg-4">
            <div class="input-group shadow-sm rounded-3">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" 
                       x-model="search" 
                       @input="page = 1" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="Buscar por nombre, DNI, CMP o correo...">
            </div>
        </div>
        <div class="col-md-7 col-lg-8 text-md-end text-muted small">
            Mostrando <span class="fw-bold text-dark" x-text="filteredUsers().length"></span> de <span class="fw-bold text-dark">{{ $users->count() }}</span> registros totales en el sistema.
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <span class="fw-bold text-dark"><i class="fa-solid fa-list text-secondary me-2"></i>Listado Médico Activo</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light text-secondary small fw-bold text-uppercase">
                    <tr>
                        <th class="ps-4" style="width: 25%;">Nombres / Usuario</th>
                        <th style="width: 20%;">Documentos Clave</th>
                        <th style="width: 20%;">Contacto Electrónico</th>
                        <th style="width: 15%;">Rol Asignado</th>
                        <th class="text-center" style="width: 10%;">Estado</th>
                        <th class="text-end pe-4" style="width: 10%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="user in paginatedUsers()" :key="user.id">
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark mb-0" x-text="user.name"></div>
                                <span class="text-muted small">@<span x-text="user.username || 'sin_username'"></span></span>
                            </td>
                            <td>
                                <div><span class="badge bg-light text-dark fw-normal">DNI:</span> <code class="text-dark" x-text="user.dni || 'N/A'"></code></div>
                                <div class="mt-1" x-show="user.cmp">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-normal">CMP:</span> <code x-text="user.cmp"></code>
                                </div>
                                <div class="mt-1" x-show="user.rne">
                                    <span class="badge bg-success bg-opacity-10 text-success fw-normal">RNE:</span> <code x-text="user.rne"></code>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary">
                                    <i class="fa-regular fa-envelope me-2 text-muted"></i><span x-text="user.email"></span>
                                </span>
                            </td>
                            <td>
                                <template x-if="user.role === 'superadmin'">
                                    <span class="badge bg-danger rounded-pill px-3"><i class="fa-solid fa-shield-halved me-1"></i> SuperAdmin</span>
                                </template>
                                <template x-if="user.role === 'admin'">
                                    <span class="badge bg-warning text-dark rounded-pill px-3"><i class="fa-solid fa-lock me-1"></i> Admin</span>
                                </template>
                                <template x-if="user.role === 'medico'">
                                    <span class="badge bg-primary rounded-pill px-3"><i class="fa-solid fa-user-md me-1"></i> Médico Nefrólogo</span>
                                </template>
                                <template x-if="user.role === 'enfermera'">
                                    <span class="badge bg-info text-dark rounded-pill px-3"><i class="fa-solid fa-user-nurse me-1"></i> Enfermería</span>
                                </template>
                            </td>
                            <td class="text-center">
                                <template x-if="user.state === 'ACTIVO'">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                                        <i class="fa-solid fa-circle text-xs me-1" style="font-size: 0.55rem;"></i> Activo
                                    </span>
                                </template>
                                <template x-if="user.state !== 'ACTIVO'">
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1">
                                        <i class="fa-solid fa-circle text-xs me-1" style="font-size: 0.55rem;"></i> Inactivo
                                    </span>
                                </template>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-light border rounded-3 me-1 text-info" 
                                        @click="openEdit(user)" title="Editar Ficha Clínica">
                                    <i class="fa-solid fa-user-pen"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border rounded-3 text-danger" 
                                        @click="deleteUser('/users/' + user.id, user.name)" title="Revocar Acceso del Sistema">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="filteredUsers().length === 0">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-user-slash display-6 mb-2 d-block text-black-50 opacity-25"></i>
                            No se encontraron registros clínicos que coincidan con "<span class="fw-bold" x-text="search"></span>".
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3 border-top-0" x-show="totalPages() > 1">
            <div class="small text-muted">
                Mostrando página <span class="fw-bold text-dark" x-text="page"></span> de <span class="fw-bold text-dark" x-text="totalPages()"></span>
            </div>
            <nav aria-label="Navegación de personal">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item" :class="{ 'disabled': page === 1 }">
                        <button class="page-link rounded-start-3" type="button" @click="page--" :disabled="page === 1">
                            <i class="fa-solid fa-angle-left me-1"></i> Anterior
                        </button>
                    </li>
                    <template x-for="p in totalPages()" :key="p">
                        <li class="page-item" :class="{ 'active': page === p }">
                            <button class="page-link px-3" type="button" x-text="p" @click="page = p"></button>
                        </li>
                    </template>
                    <li class="page-item" :class="{ 'disabled': page === totalPages() }">
                        <button class="page-link rounded-end-3" type="button" @click="page++" :disabled="page === totalPages()">
                            Siguiente <i class="fa-solid fa-angle-right ms-1"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="modal fade" id="crudUserModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-light py-3 border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-user-shield text-primary me-2"></i>
                        <span x-text="isEdit ? 'Modificar Datos de Personal Técnico' : 'Registrar Nuevo Perfil Clínico'"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form :action="isEdit ? '/users/' + form.id : '{{ route('users.store') }}'" method="POST" autocomplete="off">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nombres y Apellidos Completos</label>
                                <input type="text" name="name" x-model="form.name" class="form-control rounded-3" required placeholder="Ej: Dra. María Rostorowski">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nombre de Usuario (Login)</label>
                                <input type="text" name="username" x-model="form.username" class="form-control rounded-3" placeholder="Ej: mrostorowski">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Número de DNI</label>
                                <input type="text" name="dni" x-model="form.dni" class="form-control rounded-3" maxlength="8" placeholder="8 dígitos obligatorios">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Colegio Médico (CMP)</label>
                                <input type="text" name="cmp" x-model="form.cmp" class="form-control rounded-3" placeholder="Obligatorio si es Médico" :required="form.role === 'medico'">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Especialidad (RNE)</label>
                                <input type="text" name="rne" x-model="form.rne" class="form-control rounded-3" placeholder="N° Registro de Especialidad">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Rol Funcional</label>
                                <select name="role" x-model="form.role" class="form-select rounded-3" id="tom-role-select" required>
                                    <option value="medico">Médico Nefrólogo</option>
                                    <option value="enfermera">Enfermería</option>
                                    <option value="admin">Administrador del Sistema</option>
                                    <option value="superadmin">SuperAdministrador</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Estado Operativo</label>
                                <select name="state" x-model="form.state" class="form-select rounded-3" required>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Correo Institucional</label>
                                <input type="email" name="email" x-model="form.email" class="form-control rounded-3" required placeholder="correo@clinicarenal.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Contraseña de Seguridad</label>
                                <input type="password" name="password" class="form-control rounded-3" :required="!isEdit" :placeholder="isEdit ? 'Dejar vacío si no se modificará' : 'Mínimo 6 caracteres'">
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer bg-light border-top-0 py-3 rounded-bottom-4">
                        <button type="button" class="btn btn-light border px-4 rounded-3 text-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold" x-text="isEdit ? 'Guardar Cambios' : 'Confirmar Alta'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="hidden-delete-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
    // Definimos la función y la exponemos globalmente de inmediato para que Alpine la vea al instante
    window.userModule = function() {
        return {
            search: '',
            page: 1,
            perPage: 10,
            usersList: @json($users), 

            isEdit: false,
            modalInstance: null,
            tomSelectInstance: null,
            form: { id: '', name: '', username: '', dni: '', cmp: '', rne: '', role: 'medico', state: 'ACTIVO', email: '' },

            init() {
                // Verificamos que Bootstrap exista globalmente antes de inicializar el modal
                if (typeof bootstrap !== 'undefined') {
                    this.modalInstance = new bootstrap.Modal(document.getElementById('crudUserModal'));
                } else {
                    console.error("Bootstrap no está cargado globalmente en window.bootstrap");
                }
                
                // Inicializamos TomSelect de forma segura
                if (document.getElementById('tom-role-select')) {
                    this.tomSelectInstance = new TomSelect('#tom-role-select', {
                        create: false,
                        controlInput: null,
                        dropdownParent: 'body'
                    });
                }
            },

            filteredUsers() {
                if (!this.search || this.search.trim() === '') {
                    return this.usersList;
                }
                let query = this.search.toLowerCase().trim();
                return this.usersList.filter(user => {
                    return (user.name && user.name.toLowerCase().includes(query)) ||
                           (user.dni && user.dni.includes(query)) ||
                           (user.email && user.email.toLowerCase().includes(query)) ||
                           (user.cmp && user.cmp.includes(query)) ||
                           (user.rne && user.rne.includes(query)) ||
                           (user.username && user.username.toLowerCase().includes(query));
                });
            },

            paginatedUsers() {
                let start = (this.page - 1) * this.perPage;
                let end = start + this.perPage;
                return this.filteredUsers().slice(start, end);
            },

            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.perPage);
            },

            openCreate() {
                this.isEdit = false;
                this.form = { id: '', name: '', username: '', dni: '', cmp: '', rne: '', role: 'medico', state: 'ACTIVO', email: '' };
                if (this.tomSelectInstance) this.tomSelectInstance.setValue('medico');
                if (this.modalInstance) this.modalInstance.show();
            },

            openEdit(user) {
                this.isEdit = true;
                this.form = Object.assign({}, user);
                if (this.tomSelectInstance) this.tomSelectInstance.setValue(user.role);
                if (this.modalInstance) this.modalInstance.show();
            },

            deleteUser(routeUrl, userName) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¿Confirmar eliminación?',
                        text: `El usuario "${userName}" perderá de inmediato todo acceso a la unidad clínica.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e63946',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, revocar acceso',
                        cancelButtonText: 'Mantener activo',
                        background: '#ffffff',
                        customClass: { popup: 'rounded-4' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let f = document.getElementById('hidden-delete-form');
                            f.action = routeUrl;
                            f.submit();
                        }
                    });
                }
            }
        };
    };

    // Registrar también en el nuevo formato de Alpine por si corre encapsulado por Vite
    document.addEventListener('alpine:init', () => {
        Alpine.data('userModule', window.userModule);
    });
</script>
@endpush
@endsection