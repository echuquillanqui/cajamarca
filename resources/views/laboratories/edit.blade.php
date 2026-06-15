@extends('layouts.app')

@section('content')
<div class="container px-4">
    <div class="mb-4">
        <a href="{{ route('laboratories.index') }}" class="btn btn-sm btn-light border text-secondary px-3 rounded-pill mb-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h3 mb-0" style="color: #0f3057; font-weight: 700;">
            <i class="fa-solid fa-file-medical text-primary me-2"></i>Monitoreo de Laboratorio - Pacientes en Hemodiálisis
        </h1>
    </div>

    <form action="{{ route('laboratories.update', $laboratory) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-xl-4 col-lg-5">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="fa-solid fa-circle-info me-2"></i>Información del Registro
                    </div>
                    <div class="card-body">
                        <div class="p-3 bg-light rounded-3 mb-4 border-start border-4 border-primary">
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Paciente</h6>
                            <p class="fs-5 fw-bold text-dark mb-0">{{ $laboratory->patient->nombre ?? 'Paciente Desconocido' }}</p>
                            <small class="text-muted">Orden Asociada: #{{ $laboratory->order->codigo ?? $laboratory->order_id }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label fw-semibold">Fecha del Examen <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" id="fecha" 
                                   class="form-control @error('fecha') is-invalid @enderror" 
                                   value="{{ old('fecha', $laboratory->fecha ? $laboratory->fecha->format('Y-m-d') : date('Y-m-d')) }}" required>
                            @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label fw-semibold">Tipo / Nombre del Grupo de Examen</label>
                            <input type="text" name="tipo" id="tipo" 
                                   class="form-control @error('tipo') is-invalid @enderror" 
                                   placeholder="Ej: Monitoreo Mensual, Control Post-Diálisis"
                                   value="{{ old('tipo', $laboratory->tipo ?? 'Monitoreo de Hemodiálisis') }}">
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label fw-semibold">Observaciones / Evolución</label>
                            <textarea name="observaciones" id="observaciones" rows="5" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      placeholder="Anotaciones médicas o incidencias con la muestra...">{{ old('observaciones', $laboratory->observaciones) }}</textarea>
                            @error('observaciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card h-100" x-data="laboratorioManager({{ json_encode($laboratory->resultados ?? []) }})">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-vial text-secondary me-2"></i>Resultados de Analítica</span>
                        <button type="button" @click="addFila()" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="fa-solid fa-plus me-1"></i> Agregar Examen
                        </button>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info py-2 px-3 small rounded-3 mb-4">
                            <i class="fa-solid fa-circle-question me-1"></i> Use el buscador dinámico para seleccionar un examen del listado oficial del hospital.
                        </div>

                        <div class="dynamic-rows" style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
                            
                            <template x-for="(item, index) in rows" :key="item.id">
                                <div class="row g-2 mb-3 align-items-center bg-light p-2 rounded-3 border positional-row">
                                    
                                    <div class="col-md-7 col-sm-6">
                                        <label class="small text-muted d-block mb-1">Examen / Parámetro Médico</label>
                                        <select :name="'resultados['+index+'][clave]'" 
                                                x-model="item.clave" 
                                                x-tom-select 
                                                class="form-select form-select-sm" 
                                                required>
                                            <option value="">Buscar examen...</option>
                                            
                                            <optgroup label="FUNCIÓN RENAL">
                                                <option value="UREA mg/dl">UREA (mg/dl)</option>
                                                <option value="CREATININA mg/dl">CREATININA (mg/dl)</option>
                                                <option value="PROTEINURIA 24 H">PROTEINURIA 24 H</option>
                                                <option value="ALBUMINURIA 24 H">ALBUMINURIA 24 H</option>
                                                <option value="CKD EPI 2021">CKD EPI 2021</option>
                                            </optgroup>
                                            
                                            <optgroup label="HEMOGRAMA - COAGULACIÓN - REACTANTES FASE AGUDA">
                                                <option value="LEUCOCITOS">LEUCOCITOS</option>
                                                <option value="ABASTONADOS">ABASTONADOS</option>
                                                <option value="PLAQUETAS">PLAQUETAS</option>
                                                <option value="PCR">PCR</option>
                                                <option value="VSG">VSG</option>
                                                <option value="PROCALCITONINA">PROCALCITONINA</option>
                                                <option value="TTPa">TTPa</option>
                                                <option value="TP - INR">TP - INR</option>
                                            </optgroup>
                                            
                                            <optgroup label="PERFIL D'ANEMIA">
                                                <option value="HEMOGLOBINA">HEMOGLOBINA</option>
                                                <option value="HEMATOCRITO">HEMATOCRITO</option>
                                                <option value="FERRITINA">FERRITINA</option>
                                                <option value="Sat. TRANSFERRINA">Sat. TRANSFERRINA</option>
                                                <option value="B12">B12</option>
                                                <option value="ÁCIDO FÓLICO">ÁCIDO FÓLICO</option>
                                                <option value="RETICULOCITOS">RETICULOCITOS</option>
                                            </optgroup>
                                            
                                            <optgroup label="MEDIO INTERNO - OXIGENACIÓN">
                                                <option value="pH">pH</option>
                                                <option value="pCO2">pCO₂</option>
                                                <option value="HCO3">HCO₃</option>
                                                <option value="K+">K⁺</option>
                                                <option value="Na+">Na⁺</option>
                                                <option value="Cl-">Cl⁻</option>
                                                <option value="PaO2/FiO2">PaO₂/FiO₂</option>
                                            </optgroup>
                                            
                                            <optgroup label="METABOLISMO MINERAL ÓSEO - IONES DIVALENTES">
                                                <option value="Calcio">Calcio</option>
                                                <option value="Fósforo">Fósforo</option>
                                                <option value="PTHi">PTHi</option>
                                                <option value="Fosfatasa alcalina">Fosfatasa alcalina</option>
                                                <option value="Magnesio">Magnesio</option>
                                            </optgroup>
                                            
                                            <optgroup label="FUNCIÓN HEPÁTICA">
                                                <option value="TGP/ALT">TGP/ALT</option>
                                                <option value="TGO/AST">TGO/AST</option>
                                                <option value="PROTEINAS TOTALES">PROTEINAS TOTALES</option>
                                                <option value="ALBUMINA">ALBUMINA</option>
                                                <option value="BILIRRUBINAS TOTALES">BILIRRUBINAS TOTALES</option>
                                                <option value="BILIRRUBINA DIRECTA">BILIRRUBINA DIRECTA</option>
                                            </optgroup>
                                            
                                            <optgroup label="PERFIL LÍPIDICO">
                                                <option value="COLESTEROL TOTAL">COLESTEROL TOTAL</option>
                                                <option value="LDL">LDL</option>
                                                <option value="HDL">HDL</option>
                                                <option value="TG">TG (Triglicéridos)</option>
                                            </optgroup>
                                            
                                            <optgroup label="AUTOINMUNIDAD">
                                                <option value="ANA">ANA</option>
                                                <option value="ANCA p">ANCA p</option>
                                                <option value="ANCA c">ANCA c</option>
                                                <option value="FACTOR REUMATOIDEO">FACTOR REUMATOIDEO</option>
                                                <option value="COOMBS DIRECTO">COOMBS DIRECTO</option>
                                            </optgroup>
                                            
                                            <optgroup label="EXAMEN DE ORINA">
                                                <option value="LEUCOCITOS (ORINA)">LEUCOCITOS (Orina)</option>
                                                <option value="HEMATIES">HEMATIES</option>
                                                <option value="HEMATIES DISMORFICOS">HEMATIES DISMORFICOS</option>
                                                <option value="CILINDROS HEMATICOS">CILINDROS HEMATICOS</option>
                                                <option value="CILINDROS CEREOS">CILINDROS CEREOS</option>
                                                <option value="ALBUMINURIA (CUALITATIVA)">ALBUMINURIA (CUALITATIVA)</option>
                                                <option value="GLUCOSURIA">GLUCOSURIA</option>
                                            </optgroup>
                                            
                                            <optgroup label="OTROS">
                                                <option value="DIURESIS DIARIA">DIURESIS DIARIA</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 col-sm-5">
                                        <label class="small text-muted d-block mb-1">Resultado</label>
                                        <input type="text" :name="'resultados['+index+'][valor]'" x-model="item.valor" 
                                               class="form-control form-control-sm" placeholder="Ingrese el valor" required>
                                    </div>
                                    
                                    <div class="col-md-1 col-sm-1 text-center mt-3">
                                        <button type="button" @click="removeFila(index)" class="btn btn-sm btn-outline-danger border-0 rounded-circle">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                    
                                </div>
                            </template>

                            <div x-show="rows.length === 0" class="text-center py-5 text-muted bg-light rounded-3 border border-dashed">
                                <i class="fa-solid fa-rectangle-list fa-2x mb-3 d-block text-black-50"></i>
                                No se han seleccionado exámenes para este registro. Presione "Agregar Examen".
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 p-4 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm" style="background-color: var(--hc-primary);">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Guardar Resultados
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Directiva personalizada de Alpine para enlazar Tom Select limpiamente con la reactividad
        Alpine.directive('tom-select', (el, {}, { cleanup }) => {
            // Inicializar Tom Select en el elemento select
            let ts = new TomSelect(el, {
                create: true, // Permite escribir uno nuevo si no está en la lista estándar
                sortField: { field: "text", direction: "asc" },
                dropdownParent: 'body',
                plugins: ['dropdown_input'] // Caja de búsqueda integrada limpia
            });

            // Sincronizar el cambio de valor de Tom Select hacia Alpine
            ts.on('change', (value) => {
                el.dispatchEvent(new Event('input', { bubbles: true }));
            });

            // Destruir la instancia si se elimina la fila para evitar fugas de memoria
            cleanup(() => {
                ts.destroy();
            });
        });
    });

    function laboratorioManager(initialData) {
        return {
            rows: Object.entries(initialData).map(([key, val]) => {
                // Soportar estructuras tanto lineales como sub-arrays de Laravel
                if(typeof val === 'object' && val !== null) {
                    return { id: 'row-' + Math.random(), clave: val.clave || '', valor: val.valor || '' };
                }
                return { id: 'row-' + Math.random(), clave: key, valor: val };
            }),

            addFila() {
                // Añadimos una ID única aleatoria para que el ciclo x-for de Alpine mantenga la referencia física
                this.rows.push({ id: 'row-' + Math.random(), clave: '', valor: '' });
            },

            removeFila(index) {
                this.rows.splice(index, 1);
            }
        }
    }
</script>
@endpush