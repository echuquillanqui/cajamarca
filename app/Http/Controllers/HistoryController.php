<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = History::with(['order', 'patient', 'user']);

        // 1. Filtro de Fecha de Ingreso: Por defecto el día de hoy si no se especifica
        if ($request->filled('fecha_filtro')) {
            $query->whereDate('fecha_ingreso_hd', $request->fecha_filtro);
        } else if (!$request->has('clear_filters')) {
            // Al entrar por primera vez sin intención de limpiar, filtra el día de hoy
            $query->whereDate('fecha_ingreso_hd', Carbon::today());
        }

        // 2. Filtro por Nombres
        if ($request->filled('paciente_nombre')) {
            $nombre = $request->paciente_nombre;
            $query->whereHas('patient', function ($p) use ($nombre) {
                $p->where('nombre', 'LIKE', '%' . $nombre . '%');
            });
        }

        // 3. Filtro por DNI del Paciente
        if ($request->filled('paciente_dni')) {
            $dni = $request->paciente_dni;
            $query->whereHas('patient', function ($p) use ($dni) {
                $p->where('dni', 'LIKE', '%' . $dni . '%');
            });
        }

        // 4. Filtro por Tipo de Acceso Vascular
        if ($request->filled('tipo_acceso')) {
            $query->where('tipo', $request->tipo_acceso);
        }

        // 5. Paginación de 15 registros conservando los parámetros de búsqueda en la URL
        $histories = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        $search = $request->get('paciente_nombre', '');

        return view('histories.index', compact('histories', 'request', 'search'));
    }

    public function edit(History $history)
    {
        return view('histories.edit', compact('history'));
    }

    public function update(Request $request, History $history)
    {
        // 1. Validamos todos los campos incluyendo la estructura real proveniente de la grilla HTML
        $validatedData = $request->validate([
            'fecha_ingreso_hd'     => 'required|date',
            'serv_origen'          => 'nullable|string|max:25',
            'cama'          => 'nullable|string|max:25',
            'tiempo_enfermedad'    => 'nullable|string|max:50',
            'inicio_enfermedad'    => 'nullable|string|max:50',
            'curso_enfermedad'     => 'nullable|string|max:50',
            'relato_cronologico'   => 'nullable|string',

            'apetito'              => 'nullable|string|max:30',
            'sed'                  => 'nullable|string|max:30',
            'heces'                => 'nullable|string|max:30',
            'sueno'                => 'nullable|string|max:30',
            'diuresis_ingreso'     => 'nullable|string|max:50',

            'antecedentes_familiares' => 'nullable|string',
            'alergias'             => 'nullable|string',
            
            'biopsia_renal'        => 'nullable|boolean',
            'biopsia_renal_anio'   => 'nullable|string|max:4',
            'biopsia_renal_resultado' => 'nullable|string|max:255',

            // Examen Físico Funcional
            'pa'                   => 'nullable|string|max:15',
            'fc'                   => 'nullable|integer|min:0',
            'fr'                   => 'nullable|integer|min:0',
            'sat_o2'               => 'nullable|integer|min:0|max:100',
            'peso_ingreso'         => 'nullable|numeric|between:0,999.99',
            'talla_ingreso'        => 'nullable|numeric|between:0,9.99',
            'fio'                  => 'nullable|numeric|between:0,999.99',
            
            // Textos de Revisión de Sistemas
            'aspecto_general'      => 'nullable|string',
            'piel'                 => 'nullable|string',
            'tcsc'                 => 'nullable|string',
            'respiratorio'         => 'nullable|string',
            'cardiovascular'       => 'nullable|string',
            'abdomen'              => 'nullable|string|max:100',
            'g_urinario'           => 'nullable|string|max:100',
            'neurologico'          => 'nullable|string|max:100',
            'e_nutricional'        => 'nullable|string|max:100',

            // Acceso Vascular 1
            'tipo'                 => 'nullable|in:CVC TUNELIZADO,CVC TEMPORAL,FAV,INJERTO',
            'localizacion'         => 'nullable|in:RADIAL,HUMERAL,CERVICAL,FEMORAL,OTROS',
            'lado'                 => 'nullable|in:DERECHA,IZQUIERDA',
            'estado'               => 'nullable|in:BUENO,MALO,REGULAR',

            // Otras Terapias Previas
            'd_peritoneal'         => 'nullable|boolean',
            't_renal'              => 'nullable|boolean',

            // Datos Causa de Pérdida / Otros Accesos
            'o_tipos'              => 'nullable|string|max:50',
            'o_fecha'              => 'nullable|date',
            'o_causa'              => 'nullable|string|max:100',

            // Serología 
            'hiv'                  => 'required|boolean',
            'hbsag'                => 'required|boolean',
            'anti_hbc'             => 'required|boolean',
            'vhc'                  => 'required|boolean',
            'anti_hbs'             => 'required|boolean',
            'rpr'                  => 'required|boolean',
            'ningun_se'            => 'nullable|string|max:100',

            // Vacunas
            'vacuna_ingreso'       => 'nullable|integer|min:0',
            'vacuna_alta'          => 'nullable|integer|min:0',
            'otras_vacunas'        => 'nullable|string|max:200',

            // Diagnósticos Categorizados
            'enf_cronica'          => 'nullable|in:G,A',
            'descrip1'             => 'nullable|string|max:50',
            'etiologia_cronica'    => 'nullable|string|max:200',
            'enf_aguda'            => 'nullable|in:1,2,3',
            'descrip2'             => 'nullable|string|max:50',
            'etiologia_aguda'      => 'nullable|string|max:200',
            'motivo_hospt_act'     => 'nullable|string',

            // Cierre Clínico
            'f_alta'               => 'nullable|date',
            'consideraciones_alta' => 'nullable|string|max:255',
            'motivo_fallece'       => 'nullable|string',
            'pendientes'           => 'nullable|string',
            'peso_seco'            => 'nullable|numeric|between:0,999999.99',
            'diuresis_alta'        => 'nullable|string|max:50',

            // 🚨 CORRECCIÓN CRÍTICA: Declaramos ant_data como array válido en la solicitud
            'ant_data'             => 'nullable|array',
            'antecedentes_personales' => 'nullable|array',
        ]);

        // 2. Estructuramos el mapeo de forma limpia y segura
        $antecedentesEstructurados = [];
        if ($request->has('ant_data')) {
            foreach ($request->input('ant_data') as $key => $values) {
                $antecedentesEstructurados[$key] = [
                    'anio'       => $values['anio'] ?? '',
                    'medicacion' => $values['medicacion'] ?? ''
                ];
            }
        }

        // 3. Forzamos la asignación dentro de los datos que van a guardarse de forma masiva
        $validatedData['antecedentes_personales'] = $antecedentesEstructurados;

        // 4. Eliminamos el parámetro temporal ant_data para que no cause conflictos con columnas fantasmas
        unset($validatedData['ant_data']);

        // 5. Guardamos en la Base de Datos
        $history->update($validatedData);

        return redirect()->route('histories.edit', $history->id)
            ->with('success', 'Ficha clínica de hemodiálisis y antecedentes tricolumna actualizados correctamente.');
    }

    public function generatePdf(History $history)
    {
        // Cargamos relaciones críticas para evitar consultas flojas (lazy loading)
        $history->load(['patient', 'user', 'order']);

        // Preparar el PDF mapeando la vista HTML optimizada para impresión en tamaño A4
        $pdf = Pdf::loadView('histories.pdf', compact('history'))
                ->setPaper('a4', 'portrait')
                ->setWarnings(false);

        // Retorna el stream de descarga con el nombre estandarizado del expediente
        $filename = 'HC-ING-' . ($history->patient?->dni ?? $history->id) . '.pdf';
        return $pdf->stream($filename);
    }
}