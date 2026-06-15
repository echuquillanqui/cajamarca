<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>HISTORIA CLINICA DE INGRESO A HEMODIALISIS</title>
    <style>
        /* Configuración estricta de márgenes A4 para evitar desbordes */
        @page { 
            margin: 15px 25px; 
        }
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 9.5px; 
            color: #000; 
            line-height: 1.2; 
        }
        
        /* Salto de página limpio */
        .page-break {
            page-break-after: always;
        }
        
        /* Encabezados compactos */
        .header-container {
            width: 100%;
            margin-bottom: 3px;
        }
        .header-text {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            line-height: 1.2;
        }
        .doc-title {
            text-align: center;
            font-size: 11.5px; 
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }

        /* Estructura de Tablas Compactas */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            table-layout: fixed; 
        }
        .main-table td, .main-table th {
            border: 1px solid #000;
            padding: 3px 5px; 
            vertical-align: middle;
            word-wrap: break-word; 
        }
        .section-bar {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
            padding: 3px 5px;
            border: 1px solid #000;
            margin-top: 4px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        /* Rejillas de Casillas */
        .box-grid {
            border-collapse: collapse;
            display: inline-table;
            vertical-align: middle;
        }
        .box-grid td {
            border: 1px solid #000 !important;
            width: 12px; 
            height: 12px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            padding: 0 !important;
            color: #0f3057;
        }

        /* TEXTO RESALTADO DEL SISTEMA */
        .filled-data {
            color: #014f86; 
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9.5px;
            display: inline-block;
            margin-left: 2px;
        }
        
        /* Checkboxes */
        .check-box {
            border: 1px solid #000;
            display: inline-block;
            width: 11px;
            height: 11px;
            text-align: center;
            line-height: 11px;
            font-weight: bold;
            font-size: 8.5px;
            margin-right: 2px;
            background-color: #fff;
            vertical-align: middle;
        }
        .check-box.active {
            background-color: #000 !important;
            color: #fff !important;
        }

        .bold { font-weight: bold; }
        .w-100 { width: 100%; }
        .text-center { text-align: center; }

        /* UBICACIÓN ABSOLUTA E INAMOVIBLE PARA LA FIRMA EN LA SEGUNDA HOJA */
        .signature-container {
            position: absolute;
            bottom: 20px;
            right: 10px;
            width: 250px;
            text-align: center;
            font-size: 9.5px;
        }
        .signature-line {
            border-top: 1px solid #000;
            padding-top: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="header-text">
            DIRECCIÓN REGIONAL DE SALUD DE CAJAMARCA<br>
            HOSPITAL REGIONAL DOCENTE DE CAJAMARCA<br>
            DEPARTAMENTO DE MEDICINA<br>
            UNIDAD DE HEMODIALISIS
        </div>
    </div>

    <div style="width: 100%; text-align: right; margin-bottom: 4px;">
        <span class="bold" style="font-size: 8.5px;">FECHA DE INGRESO A HD ACTUAL:</span>
        <table class="box-grid">
            <tr>
                @php 
                    $fechaIng = $history->fecha_ingreso_hd ? \Carbon\Carbon::parse($history->fecha_ingreso_hd)->format('dmY') : '________';
                    $charsFecha = str_split($fechaIng);
                @endphp
                @foreach($charsFecha as $c)
                    <td>{{ $c }}</td>
                @endforeach
            </tr>
        </table>
    </div>

    <div class="doc-title">HISTORIA CLÍNICA DE INGRESO A HEMODIÁLISIS</div>

    <table style="width: 100%; margin-bottom: 4px; border-collapse: collapse;">
        <tr>
            @php $finan = strtoupper($history->patient?->financiador ?? ''); @endphp
            <td style="vertical-align: middle; width: 55%;">
                <span class="bold">FINANCIADOR</span> &nbsp;&nbsp;
                <span class="check-box {{ $finan === 'SIS' ? 'active' : '' }}">{{ $finan === 'SIS' ? 'X' : '' }}</span> SIS &nbsp;
                <span class="check-box {{ $finan === 'SALUD POL' || $finan === 'SALUDPOL' ? 'active' : '' }}">{{ $finan === 'SALUD POL' || $finan === 'SALUDPOL' ? 'X' : '' }}</span> SALUD POL &nbsp;
                <span class="check-box {{ $finan === 'ESSALUD' ? 'active' : '' }}">{{ $finan === 'ESSALUD' ? 'X' : '' }}</span> EsSALUD &nbsp;
                <span class="check-box {{ $finan === 'PARTICULAR' || $finan === 'PART' ? 'active' : '' }}">{{ $finan === 'PARTICULAR' || $finan === 'PART' ? 'X' : '' }}</span> PART
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <span class="bold">HISTORIA CLÍNICA</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $dniPatient = str_pad($history->patient?->dni ?? '', 10, ' ', STR_PAD_LEFT);
                            $charsDni = str_split($dniPatient);
                        @endphp
                        @foreach($charsDni as $c)
                            <td>{{ $c }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 4px; vertical-align: middle;">
                <span class="bold">CÓDIGO DE SIS/SALUDPOL:</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $codSeguro = str_pad($history->patient?->codigo_seguro ?? '', 15, ' ', STR_PAD_RIGHT);
                            $charsSeguro = str_split(substr($codSeguro, 0, 15));
                        @endphp
                        @foreach($charsSeguro as $cs)
                            <td>{{ $cs }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-bar">DATOS DE FILIACIÓN</div>
    <table class="main-table">
        <tr>
            <td colspan="4"><span class="bold">NOMBRE:</span> <span class="filled-data">{{ $history->patient?->nombre }}</span></td>
        </tr>
        <tr>
            <td style="width: 50%;" colspan="2">
                <span class="bold">DNI/C.EXT.</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $dniCeldas = str_pad($history->patient?->dni ?? '', 8, ' ', STR_PAD_RIGHT);
                            $arrayDni = str_split(substr($dniCeldas, 0, 8));
                        @endphp
                        @foreach($arrayDni as $char)
                            <td>{{ $char }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
            <td style="width: 50%;" colspan="2">
                <span class="bold">TELÉFONO</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $telCeldas = str_pad($history->patient?->telefono ?? '', 9, ' ', STR_PAD_RIGHT);
                            $arrayTel = str_split(substr($telCeldas, 0, 9));
                        @endphp
                        @foreach($arrayTel as $tChar)
                            <td>{{ $tChar }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;" colspan="2">
                <span class="bold">FECHA DE NACIMIENTO:</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $fNac = $history->patient?->fecha_nacimiento ? \Carbon\Carbon::parse($history->patient?->fecha_nacimiento)->format('dmY') : '________';
                            $arrayNac = str_split($fNac);
                        @endphp
                        @foreach($arrayNac as $an)
                            <td>{{ $an }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
            <td style="width: 50%;" colspan="2">
                <span class="bold">EDAD:</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <table class="box-grid">
                    <tr>
                        @php 
                            $edad = $history->patient?->fecha_nacimiento ? \Carbon\Carbon::parse($history->patient?->fecha_nacimiento)->age : '  ';
                            $edadCeldas = str_split(str_pad($edad, 3, ' ', STR_PAD_LEFT));
                        @endphp
                        @foreach($edadCeldas as $ec)
                            <td>{{ $ec }}</td>
                        @endforeach
                    </tr>
                </table> 
                &nbsp;<span class="bold">AÑOS</span>
            </td>
        </tr>
        <tr>
            @php 
                $sex = strtoupper($history->patient?->sexo ?? ''); 
                $civ = strtoupper($history->patient?->civil ?? '');
                $inst = strtoupper($history->patient?->instruccion ?? '');
            @endphp
            <td style="width: 25%;">
                <span class="bold">SEXO:</span> &nbsp;&nbsp;
                <span class="check-box {{ $sex === 'M' ? 'active' : '' }}">{{ $sex === 'M' ? 'X' : '' }}</span> M &nbsp;
                <span class="check-box {{ $sex === 'F' ? 'active' : '' }}">{{ $sex === 'F' ? 'X' : '' }}</span> F
            </td>
            <td style="width: 35%;"><span class="bold">ESTADO CIVIL:</span> <span class="filled-data">{{ $civ ?: '-' }}</span></td>
            <td style="width: 40%;" colspan="2"><span class="bold">GRADO DE INSTRUCCIÓN:</span> <span class="filled-data">{{ $inst ?: '-' }}</span></td>
        </tr>
        <tr>
            <td colspan="2" style="width: 50%;"><span class="bold">PROCEDENCIA:</span> <span class="filled-data">{{ $history->patient?->procedencia ?? 'CAJAMARCA' }}</span></td>
            <td colspan="2" style="width: 50%;"><span class="bold">DIRECCIÓN:</span> <span class="filled-data">{{ $history->patient?->direccion ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="section-bar">PERSONA DE CONTACTO</div>
    <table class="main-table">
        <tr>
            <td style="width: 60%;"><span class="bold">PERSONA DE CONTACTO:</span> <span class="filled-data">{{ $history->patient?->contacto_emergencia_nombre ?? '-' }}</span></td>
            <td style="width: 40%;">
                <span class="bold">DNI:</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $cDni = str_pad($history->patient?->contacto_emergencia_dni ?? '', 8, ' ', STR_PAD_RIGHT);
                            $arrayCDni = str_split(substr($cDni, 0, 8));
                        @endphp
                        @foreach($arrayCDni as $cd)
                            <td>{{ $cd }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><span class="bold">RELACIÓN:</span> <span class="filled-data">{{ $history->patient?->contacto_emergencia_parentesco ?? '-' }}</span></td>
            <td>
                <span class="bold">TELEF.</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $cTel = str_pad($history->patient?->contacto_emergencia_telefono ?? '', 9, ' ', STR_PAD_RIGHT);
                            $arrayCTel = str_split(substr($cTel, 0, 9));
                        @endphp
                        @foreach($arrayCTel as $ct)
                            <td>{{ $ct }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <tr>
            @php $servOrigen = strtoupper($history->serv_origen ?? ''); @endphp
            <td style="width: 18%;"><span class="bold">SERVICIO DE ORIGEN:</span></td>
            <td style="width: 62%;">
                @foreach(['URO','TOPI','TOP 2','OBS','UCI','UCIN','URPA','MED','CIRUG','GIN','PED','UCIN-NEO','C. EXT','URCA'] as $servicio)
                    <span class="check-box {{ $servOrigen === $servicio ? 'active' : '' }}">{{ $servOrigen === $servicio ? 'X' : '' }}</span> {{ $servicio }}
                @endforeach
            </td>
            <td style="width: 20%;"><span class="bold">CAMA:</span> &nbsp;<span class="filled-data">{{ $history->cama ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="section-bar">ENFERMEDAD ACTUAL</div>
    <table class="main-table">
        <tr>
            <td style="width: 33.3%;"><span class="bold">TIEMPO DE ENFERMEDAD:</span> <span class="filled-data">{{ $history->tiempo_enfermedad ?? '-' }}</span></td>
            <td style="width: 33.3%;"><span class="bold">INICIO:</span> <span class="filled-data">{{ $history->inicio_enfermedad ?? '-' }}</span></td>
            <td style="width: 33.3%;"><span class="bold">CURSO:</span> <span class="filled-data">{{ $history->curso_enfermedad ?? '-' }}</span></td>
        </tr>
        <tr>
            <td colspan="3" style="vertical-align: top; padding-bottom: 15px;"><span class="bold">RELATO CRONOLÓGICO:</span><br><span class="filled-data" style="text-transform:none;">{{ $history->relato_cronologico ?? '-' }}</span></td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="bold">FUNCIONES BIOLÓGICAS:</span> &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="bold">APETITO:</span> <span class="filled-data">{{ $history->apetito ?? '-' }}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
                <span class="bold">SED:</span> <span class="filled-data">{{ $history->sed ?? '-' }}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
                <span class="bold">HECES:</span> <span class="filled-data">{{ $history->heces ?? '-' }}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
                <span class="bold">SUEÑO:</span> <span class="filled-data">{{ $history->sueno ?? '-' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="3"><span class="bold">DIURESIS AL MOMENTO DE INGRESO A HD ACTUAL:</span> <span class="filled-data">{{ $history->diuresis_ingreso ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="section-bar">ANTECEDENTES PERSONALES</div>
    <table class="main-table text-center">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="text-align: left; width: 19%;">CONDICIÓN</th>
                <th style="width: 7%;">AÑO Dx</th>
                <th style="text-align: left; width: 18%;">MEDICACIÓN PREVIA</th>
                <th style="text-align: left; width: 19%;">CONDICIÓN</th>
                <th style="width: 7%;">AÑO Dx</th>
                <th style="text-align: left; width: 18%;">MEDICACIÓN PREVIA</th>
                <th style="text-align: left; width: 12%;">OTROS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $ant = $history->antecedentes_personales ?? [];
                $leftAntecedentes = [
                    ['diabetes', 'DIABETES MELLITUS'],
                    ['hta', 'HIPERTENSIÓN'],
                    ['enfermedad_cv', 'ENFERMEDAD CV'],
                    ['glomerulonefritis', 'GLOMERULONEFRITIS'],
                    ['vasculitis', 'VASCULITIS'],
                    ['les', 'LES'],
                ];
                $rightAntecedentes = [
                    ['uropatia_obs', 'UROPATÍA OBSTRUCTIVA'],
                    ['litiasis', 'LITIASIS URINARIA'],
                    ['quistes_erpo', 'QUISTES/ERPQ'],
                    ['tuberculosis', 'TUBERCULOSIS'],
                    ['erc', 'ERC'],
                    ['cirugias', 'CIRUGÍAS PREVIAS'],
                ];
                $otrosAntecedentes = [
                    ['obesidad', 'OBESIDAD'],
                    ['tabaquismo', 'TABAQUISMO'],
                    ['alcoholismo', 'ALCOHOLISMO'],
                    ['sedentarismo', 'SEDENTARISMO'],
                    ['transfusiones', 'TRANSFUSIONES'],
                    ['otros', 'OTRAS'],
                ];
            @endphp
            @foreach($leftAntecedentes as $idx => $left)
                @php
                    $right = $rightAntecedentes[$idx];
                    $otro = $otrosAntecedentes[$idx];
                    $otroMarcado = !empty($ant[$otro[0]]['anio']) || !empty($ant[$otro[0]]['medicacion']);
                @endphp
                <tr>
                    <td style="text-align: left;"><span class="bold">{{ $left[1] }}</span></td>
                    <td><span class="filled-data">{{ $ant[$left[0]]['anio'] ?? '' }}</span></td>
                    <td style="text-align: left;"><span class="filled-data">{{ $ant[$left[0]]['medicacion'] ?? '' }}</span></td>
                    <td style="text-align: left;"><span class="bold">{{ $right[1] }}</span></td>
                    <td><span class="filled-data">{{ $ant[$right[0]]['anio'] ?? '' }}</span></td>
                    <td style="text-align: left;"><span class="filled-data">{{ $ant[$right[0]]['medicacion'] ?? '' }}</span></td>
                    <td style="text-align: left;"><span class="bold">{{ $otro[1] }}</span> <span class="check-box {{ $otroMarcado ? 'active' : '' }}">{{ $otroMarcado ? 'X' : '' }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="main-table">
        <tr>
            <td><span class="bold">ANTECEDENTES FAMILIARES RELEVANTES:</span> <span class="filled-data" style="text-transform:none;">{{ $history->antecedentes_familiares ?? '-' }}</span></td>
        </tr>
        <tr>
            <td><span class="bold">ALERGIAS:</span> <span class="filled-data">{{ $history->alergias ?? '-' }}</span></td>
        </tr>
    </table>

    <table class="main-table">
        <tr style="background-color: #f2f2f2;"><td colspan="3" class="bold">BIOPSIA RENAL</td></tr>
        <tr>
            <td style="width: 25%;"><span class="bold">REALIZÓ:</span> <span class="filled-data">{{ $history->biopsia_renal ? 'SÍ' : 'NO' }}</span></td>
            <td style="width: 25%;"><span class="bold">AÑO:</span> <span class="filled-data">{{ $history->biopsia_renal_anio ?? '-' }}</span></td>
            <td style="width: 50%;"><span class="bold">RESULTADO:</span> <span class="filled-data" style="text-transform:none;">{{ $history->biopsia_renal_resultado ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <div class="section-bar" style="margin-top: 0px;">EXAMEN FÍSICO DE INGRESO</div>
    <table class="main-table">
        <tr>
            <td style="width: 25%;"><span class="bold">PA:</span> <span class="filled-data">{{ $history->pa ?? '-' }}</span> mmHg</td>
            <td style="width: 25%;"><span class="bold">FC:</span> <span class="filled-data">{{ $history->fc ?? '-' }}</span> lpm</td>
            <td style="width: 25%;"><span class="bold">FR:</span> <span class="filled-data">{{ $history->fr ?? '-' }}</span> rpm</td>
            <td style="width: 25%;"><span class="bold">SatO₂:</span> <span class="filled-data">{{ $history->sat_o2 ?? '-' }}</span> %</td>
        </tr>
        <tr>
            <td colspan="2"><span class="bold">PESO INGRESO:</span> <span class="filled-data">{{ $history->peso_ingreso ?? '-' }}</span> kg</td>
            <td><span class="bold">TALLA:</span> <span class="filled-data">{{ $history->talla_ingreso ?? '-' }}</span> m</td>
            <td><span class="bold">FiO₂:</span> <span class="filled-data">{{ $history->fio ?? '-' }}</span></td>
        </tr>
        <tr>
            <td colspan="4" style="vertical-align: top; line-height: 1.35; padding-bottom: 10px;">
                <span class="bold">ASPECTO GENERAL:</span> <span class="filled-data" style="text-transform:none;">{{ $history->aspecto_general ?? '-' }}</span><br>
                <span class="bold">PIEL / TCSC:</span> <span class="filled-data" style="text-transform:none;">{{ $history->piel ?? '-' }} / {{ $history->tcsc ?? '-' }}</span><br>
                <span class="bold">CARDIOVASCULAR / RESPIRATORIO:</span> <span class="filled-data" style="text-transform:none;">{{ $history->cardiovascular ?? '-' }} | {{ $history->respiratorio ?? '-' }}</span><br>
                <span class="bold">ABDOMEN:</span> <span class="filled-data" style="text-transform:none;">{{ $history->abdomen ?? '-' }}</span><br>
                <span class="bold">GÉNITO URINARIO:</span> <span class="filled-data" style="text-transform:none;">{{ $history->g_urinario ?? '-' }}</span><br>
                <span class="bold">NEUROLÓGICO:</span> <span class="filled-data" style="text-transform:none;">{{ $history->neurologico ?? '-' }}</span><br>
                <span class="bold">ESTADO NUTRICIONAL:</span> <span class="filled-data" style="text-transform:none;">{{ $history->e_nutricional ?? '-' }}</span>
            </td>
        </tr>
    </table>

    <div class="section-bar">ACCESO VASCULAR</div>
    <table class="main-table">
        <tr>
            <td colspan="3" style="border-bottom: none; padding-bottom: 2px;">
                <span class="bold">Fecha acceso vascular actual:</span> &nbsp;
                <table class="box-grid">
                    <tr>
                        @php 
                            $fechaAcc = $history->fecha_ingreso_hd ? \Carbon\Carbon::parse($history->fecha_ingreso_hd)->format('dmY') : '________';
                            $charsAcc = str_split($fechaAcc);
                        @endphp
                        @foreach($charsAcc as $ca)
                            <td>{{ $ca }}</td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            @php 
                $tipoAcc = strtoupper($history->tipo ?? ''); 
                $eAcc = strtoupper($history->estado ?? '');
                $locAcc = strtoupper($history->localizacion ?? '');
                $ladoAcc = strtoupper($history->lado ?? '');
            @endphp
            <td style="width: 34%; vertical-align: top; border-right: none;">
                <span class="bold" style="display:block; margin-bottom:2px;">TIPO:</span>
                <span class="check-box {{ $tipoAcc === 'FAV' ? 'active' : '' }}">{{ $tipoAcc === 'FAV' ? 'X' : '' }}</span> FAV<br>
                <span class="check-box {{ $tipoAcc === 'INJERTO' ? 'active' : '' }}">{{ $tipoAcc === 'INJERTO' ? 'X' : '' }}</span> INJERTO<br>
                <span class="check-box {{ $tipoAcc === 'CVC TUNELIZADO' ? 'active' : '' }}">{{ $tipoAcc === 'CVC TUNELIZADO' ? 'X' : '' }}</span> CVC TUNELIZADO<br>
                <span class="check-box {{ $tipoAcc === 'CVC TEMPORAL' ? 'active' : '' }}">{{ $tipoAcc === 'CVC TEMPORAL' ? 'X' : '' }}</span> CVC TEMPORAL<br><br>

                <span class="bold" style="display:block; margin-bottom:2px;">FLUJO:</span>
                <span class="check-box {{ $eAcc === 'BUENO' ? 'active' : '' }}">{{ $eAcc === 'BUENO' ? 'X' : '' }}</span> BUENO<br>
                <span class="check-box {{ $eAcc === 'MALO' ? 'active' : '' }}">{{ $eAcc === 'MALO' ? 'X' : '' }}</span> MALO<br>
                <span class="check-box {{ $eAcc === 'REGULAR' ? 'active' : '' }}">{{ $eAcc === 'REGULAR' ? 'X' : '' }}</span> REGULAR
            </td>

            <td style="width: 33%; vertical-align: top; border-left: none; border-right: none;">
                <span class="bold" style="display:block; margin-bottom:2px;">LOCALIZACIÓN:</span>
                <span class="check-box {{ $locAcc === 'RADIAL' ? 'active' : '' }}">{{ $locAcc === 'RADIAL' ? 'X' : '' }}</span> RADIAL<br>
                <span class="check-box {{ $locAcc === 'HUMERAL' ? 'active' : '' }}">{{ $locAcc === 'HUMERAL' ? 'X' : '' }}</span> HUMERAL<br>
                <span class="check-box {{ $locAcc === 'CERVICAL' ? 'active' : '' }}">{{ $locAcc === 'CERVICAL' ? 'X' : '' }}</span> CERVICAL<br>
                <span class="check-box {{ $locAcc === 'FEMORAL' ? 'active' : '' }}">{{ $locAcc === 'FEMORAL' ? 'X' : '' }}</span> FEMORAL<br>
                <span class="check-box {{ $locAcc === 'OTROS' ? 'active' : '' }}">{{ $locAcc === 'OTROS' ? 'X' : '' }}</span> OTROS
            </td>

            <td style="width: 33%; vertical-align: middle; border-left: none;">
                <span class="bold">LADO:</span><br><br>
                <div style="margin-bottom: 5px;"><span class="check-box {{ $ladoAcc === 'DERECHA' ? 'active' : '' }}">{{ $ladoAcc === 'DERECHA' ? 'X' : '' }}</span> DERECHA</div>
                <div><span class="check-box {{ $ladoAcc === 'IZQUIERDA' ? 'active' : '' }}">{{ $ladoAcc === 'IZQUIERDA' ? 'X' : '' }}</span> IZQUIERDA</div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
        <tr>
            <td style="width: 44%; border: 1px solid #000; padding: 0; vertical-align: top;">
                <div style="background-color: #f2f2f2; font-weight: bold; text-align: center; border-bottom: 1px solid #000; padding: 3px; font-size: 9px;">OTRAS TERAPIAS PREVIAS</div>
                <div style="padding: 6px; line-height: 1.5;">
                    <span class="check-box {{ $history->d_peritoneal ? 'active' : '' }}">{{ $history->d_peritoneal ? 'X' : '' }}</span> DIÁLISIS PERITONEAL<br>
                    <span class="check-box {{ $history->t_renal ? 'active' : '' }}">{{ $history->t_renal ? 'X' : '' }}</span> TRANSPLANTE RENAL
                </div>
            </td>
            <td style="width: 2%; border: none;"></td>
            <td style="width: 54%; border: 1px solid #000; padding: 0; vertical-align: top;">
                <div style="background-color: #f2f2f2; font-weight: bold; text-align: center; border-bottom: 1px solid #000; padding: 3px; font-size: 9px;">OTROS ACCESOS VASCULARES</div>
                <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                    <tr>
                        <td style="border: none; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 3px; width: 35%;"><span class="bold">TIPO:</span></td>
                        <td style="border: none; border-bottom: 1px solid #000; padding: 3px;"><span class="filled-data">{{ $history->o_tipos ?? '' }}</span></td>
                    </tr>
                    <tr>
                        <td style="border: none; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 3px;"><span class="bold">FECHA CREACIÓN:</span></td>
                        <td style="border: none; border-bottom: 1px solid #000; padding: 3px;"><span class="filled-data">{{ $history->o_fecha ? \Carbon\Carbon::parse($history->o_fecha)->format('d/m/Y') : '' }}</span></td>
                    </tr>
                    <tr>
                        <td style="border: none; border-right: 1px solid #000; padding: 3px;"><span class="bold">CAUSA CAMBIO:</span></td>
                        <td style="border: none; padding: 3px;"><span class="filled-data">{{ $history->o_causa ?? '' }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-bar">SEROLOGÍA VIRAL (Al momento de ingreso/reingreso a Unidad de Hemodiálisis)</div>
    <table class="main-table">
        <tr>
            <td style="width: 33.3%;">
                <span class="bold">HBsAg:</span> &nbsp;&nbsp; 
                <span class="check-box {{ !$history->hbsag ? 'active' : '' }}">{{ !$history->hbsag ? 'X' : ' ' }}</span> NR &nbsp;&nbsp; 
                <span class="check-box {{ $history->hbsag ? 'active' : '' }}">{{ $history->hbsag ? 'X' : ' ' }}</span> R
            </td>
            <td style="width: 33.3%;">
                <span class="bold">VIH:</span> &nbsp;&nbsp; 
                <span class="check-box {{ !$history->hiv ? 'active' : '' }}">{{ !$history->hiv ? 'X' : ' ' }}</span> NR &nbsp;&nbsp; 
                <span class="check-box {{ $history->hiv ? 'active' : '' }}">{{ $history->hiv ? 'X' : ' ' }}</span> R
            </td>
            <td style="width: 33.3%;">
                <span class="bold">VHC:</span> &nbsp;&nbsp; 
                <span class="check-box {{ !$history->vhc ? 'active' : '' }}">{{ !$history->vhc ? 'X' : ' ' }}</span> NR &nbsp;&nbsp; 
                <span class="check-box {{ $history->vhc ? 'active' : '' }}">{{ $history->vhc ? 'X' : ' ' }}</span> R
            </td>
        </tr>
        <tr>
            <td>
                <span class="bold">Anti Hbc:</span> &nbsp;&nbsp; 
                <span class="check-box {{ !$history->anti_hbc ? 'active' : '' }}">{{ !$history->anti_hbc ? 'X' : ' ' }}</span> NR &nbsp;&nbsp; 
                <span class="check-box {{ $history->anti_hbc ? 'active' : '' }}">{{ $history->anti_hbc ? 'X' : ' ' }}</span> R
            </td>
            <td>
                <span class="bold">Anti-Hbs:</span> &nbsp;&nbsp; 
                <span class="check-box {{ !$history->anti_hbs ? 'active' : '' }}">{{ !$history->anti_hbs ? 'X' : ' ' }}</span> NR &nbsp;&nbsp; 
                <span class="check-box {{ $history->anti_hbs ? 'active' : '' }}">{{ $history->anti_hbs ? 'X' : ' ' }}</span> R
            </td>
            <td>
                <span class="bold">RPR / VDRL:</span> &nbsp;&nbsp; 
                <span class="check-box {{ !$history->rpr ? 'active' : '' }}">{{ !$history->rpr ? 'X' : ' ' }}</span> NR &nbsp;&nbsp; 
                <span class="check-box {{ $history->rpr ? 'active' : '' }}">{{ $history->rpr ? 'X' : ' ' }}</span> R
            </td>
        </tr>
    </table>

    <table class="main-table">
        <tr style="background-color: #f2f2f2;"><td colspan="3" class="bold">VACUNACIÓN VIRUS HEPATITIS B</td></tr>
        <tr>
            <td style="width: 33.3%;"><span class="bold">ANTES DEL INGRESO A HD:</span> <span class="filled-data">{{ $history->vacuna_ingreso ?? '0' }}</span> dosis</td>
            <td style="width: 33.3%;"><span class="bold">AL ALTA DE HOSP. ACTUAL:</span> <span class="filled-data">{{ $history->vacuna_alta ?? '0' }}</span> dosis</td>
            <td><span class="bold">OTRAS VACUNAS:</span> <span class="filled-data">{{ $history->otras_vacunas ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="section-bar">DIAGNÓSTICO CLÍNICO</div>
    <table class="main-table">
        <tr>
            <td style="width: 80%;"><span class="bold">ENFERMEDAD RENAL CRÓNICA (Etiología):</span> <span class="filled-data">{{ $history->etiologia_cronica ?? '-' }}</span></td>
            <td style="text-align: center;"><span class="bold">ESTADIO (G/A):</span> <span class="filled-data">{{ $history->enf_cronica ?? '-' }}</span></td>
        </tr>
        <tr>
            <td><span class="bold">LESIÓN RENAL AGUDA (Etiología):</span> <span class="filled-data">{{ $history->etiologia_aguda ?? '-' }}</span></td>
            <td style="text-align: center;"><span class="bold">ESTADIO (1/2/3):</span> <span class="filled-data">{{ $history->enf_aguda ?? '-' }}</span></td>
        </tr>
        <tr>
            <td colspan="2"><span class="bold">MOTIVO DE INGRESO A HEMODIÁLISIS HOSPITALIZACIÓN ACTUAL:</span><br><span class="filled-data" style="text-transform:none;">{{ $history->motivo_hospt_act ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="section-bar">DIAGNÓSTICOS COMPLEMENTARIOS</div>
    <table class="main-table">
        @php $diagnosticosComplementarios = is_array($history->diagnostico) ? $history->diagnostico : []; @endphp
        @for($i = 1; $i <= 5; $i++)
            <tr><td><span class="bold">{{ $i }}.-</span> <span class="filled-data" style="text-transform:none;">{{ $diagnosticosComplementarios[$i - 1] ?? '' }}</span></td></tr>
        @endfor
    </table>

    <div class="section-bar">FECHA DE ALTA / CONSIDERACIONES AL ALTA</div>
    <table class="main-table">
        <tr>
            <td style="width: 20%;"><span class="bold">FECHA DE ALTA:</span> <span class="filled-data">{{ $history->f_alta ? \Carbon\Carbon::parse($history->f_alta)->format('d/m/Y') : '-' }}</span></td>
            <td style="width: 80%;">
                <span class="bold">CONSIDERACIONES:</span>
                <span class="check-box"></span> SALE DE TTM&nbsp;&nbsp;
                <span class="check-box"></span> CONTINÚA EN TTM&nbsp;&nbsp;
                <span class="check-box"></span> RETIRO VOLUNTARIO&nbsp;&nbsp;
                <span class="check-box"></span> PASA A URCA&nbsp;&nbsp;
                <span class="check-box"></span> SEGUIMIENTO EN CLÍNICA TERCERIZADA&nbsp;&nbsp;
                <span class="check-box {{ $history->motivo_fallece ? 'active' : '' }}">{{ $history->motivo_fallece ? 'X' : '' }}</span> FALLECE
            </td>
        </tr>
        <tr><td colspan="2"><span class="bold">MOTIVO:</span> <span class="filled-data" style="text-transform:none;">{{ $history->motivo_fallece ?? '-' }}</span></td></tr>
    </table>

    <div class="section-bar">PLAN DE CIERRE CLÍNICO / PENDIENTES AL ALTA</div>
    <table class="main-table">
        <tr>
            <td colspan="2"><span class="bold">CONSIDERACIONES AL ALTA:</span> <span class="filled-data" style="text-transform:none;">{{ $history->consideraciones_alta ?? '-' }}</span></td>
        </tr>
        <tr>
            <td style="width: 50%;"><span class="bold">PESO SECO AL ALTA:</span> <span class="filled-data">{{ $history->peso_seco ?? '-' }}</span> kg</td>
            <td style="width: 50%;"><span class="bold">DIURESIS RESIDUAL AL ALTA:</span> <span class="filled-data">{{ $history->diuresis_alta ?? '-' }}</span></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: top; padding-bottom: 5px;"><span class="bold">PENDIENTES AL ALTA:</span> <span class="filled-data" style="text-transform:none;">{{ $history->pendientes ?? '-' }}</span></td>
        </tr>
    </table>

    <div class="signature-container">
        <div class="signature-line">
            MÉDICO RESPONSABLE: <span class="filled-data">{{ $history->user?->name ?? 'MÉDICO TRATANTE' }}</span>
        </div>
        <div style="font-size: 8.5px; margin-top: 2px; color: #444;">
            @php
                // Extraemos el rol del usuario conectado en mayúsculas
                $userRole = $history->user && $history->user->role ? strtoupper($history->user->role) : 'MEDICO';
                
                // Mapeo dinámico de matrícula médica o de enfermería (puedes ajustar el nombre del campo de tu modelo)
                $matricula = $history->user?->matricula ?? '____________'; 
            @endphp

            @if(str_contains($userRole, 'MEDICO') || str_contains($userRole, 'DOCTOR'))
                MEDICO<br>
                CMP: <span class="filled-data">{{ $matricula }}</span>
            @else
                ENFERMERA(O)<br>
                CEP: <span class="filled-data">{{ $matricula }}</span>
            @endif
        </div>
    </div>

</body>
</html>