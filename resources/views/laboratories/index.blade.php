@extends('layouts.app')

@section('content')
<div class="container px-4" x-data="laboratorioList({{ json_encode($laboratories) }})">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h1 class="h3 mb-0" style="color: #0f3057; font-weight: 700;">
            <i class="fa-solid fa-flask text-primary me-2"></i>Monitoreo de Laboratorios
        </h1>
        
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form action="{{ route('laboratories.index') }}" method="GET" class="mb-0">
                <input type="date" name="date" class="form-control rounded-pill border-2 border-primary border-opacity-25" value="{{ $date ?? date('Y-m-d') }}" onchange="this.form.submit()">
            </form>
            <div class="position-relative" style="min-width: 350px;">
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" 
                       x-model="search" 
                       class="form-control rounded-pill ps-5 border-2 border-primary border-opacity-25" 
                       placeholder="Buscar por paciente, examen o fecha...">
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-transparent py-3">
            <span class="fw-bold text-dark">
                <i class="fa-solid fa-list-check me-2 text-secondary"></i>Historial Clínico de Exámenes
            </span>
            <span class="badge bg-secondary rounded-pill" x-text="filteredLaboratories().length + ' registros encontrados'"></span>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small fw-bold">
                        <tr>
                            <th class="ps-4" style="width: 15%;">Fecha</th>
                            <th style="width: 30%;">Paciente</th>
                            <th style="width: 25%;">Tipo de Monitoreo</th>
                            <th style="width: 15%;">N° Orden</th>
                            <th style="width: 15%;">Registrado Por</th>
                            <th class="text-end pe-4" style="width: 10%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="lab in filteredLaboratories()" :key="lab.id">
                            <tr>
                                <td class="ps-4 fw-medium text-dark" x-text="formatDate(lab.fecha)"></td>
                                
                                TR>
                                <td>
                                    <div class="fw-bold text-dark" x-text="lab.patient ? lab.patient.nombre : 'N/A'"></div>
                                    <small class="text-muted">ID Paciente: <span x-text="lab.patient_id"></span></small>
                                </td>
                                
                                <td>
                                    <span class="badge bg-info text-dark bg-opacity-10 px-2 py-1" 
                                          style="font-size: 0.85rem;"
                                          x-text="lab.tipo || 'No especificado'">
                                    </span>
                                </td>
                                
                                <td>
                                    <span class="badge bg-light text-secondary border px-2 py-1 font-monospace">
                                        #<span x-text="lab.order ? (lab.order.codigo || lab.order_id) : lab.order_id"></span>
                                    </span>
                                </td>
                                
                                <td>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-user-md text-primary opacity-70"></i>
                                        <span x-text="lab.user ? lab.user.name : 'Sistema'"></span>
                                    </small>
                                </td>
                                
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a :href="'/laboratories/' + lab.id + '/edit'" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Resultados
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredLaboratories().length === 0" style="display: none;">
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-3x mb-3 d-block text-black-50 opacity-50"></i>
                                <h5>No se encontraron resultados</h5>
                                <p class="small text-muted mb-0">Prueba modificando los términos de tu búsqueda analítica.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function laboratorioList(initialData) {
        return {
            search: '',
            laboratories: initialData,

            // Filtrado dinámico reactivo en tiempo real
            filteredLaboratories() {
                if (this.search.trim() === '') {
                    return this.laboratories;
                }
                
                const query = this.search.toLowerCase().trim();
                
                return this.laboratories.filter(lab => {
                    const pacienteNombre = lab.patient && lab.patient.nombre ? lab.patient.nombre.toLowerCase() : '';
                    const tipoExamen = lab.tipo ? lab.tipo.toLowerCase() : '';
                    const fechaExamen = lab.fecha ? lab.fecha.toLowerCase() : '';
                    const doctorNombre = lab.user && lab.user.name ? lab.user.name.toLowerCase() : '';
                    const ordenId = String(lab.order_id);

                    return pacienteNombre.includes(query) || 
                           tipoExamen.includes(query) || 
                           fechaExamen.includes(query) ||
                           doctorNombre.includes(query) ||
                           ordenId.includes(query);
                });
            },

            // Formateador de fechas auxiliar en JS para la vista
            formatDate(dateString) {
                if (!dateString) return 'Sin fecha';
                // Convierte YYYY-MM-DD o formato ISO a dd/mm/YYYY
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString;
                
                // Forzar zona horaria local para evitar desajustes de un día menos
                const day = String(date.getDate() + 1).padStart(2, '0'); 
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                
                return `${day}/${month}/${year}`;
            }
        }
    }
</script>
@endpush