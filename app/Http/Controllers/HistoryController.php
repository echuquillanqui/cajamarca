<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $query = History::with(['order', 'patient', 'user']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($p) use ($search) {
                    $p->where('nombre', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('order', function ($o) use ($search) {
                    $o->where('codigo', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('tipo', 'LIKE', '%' . $search . '%')
                ->orWhere('serv_origen', 'LIKE', '%' . $search . '%');
            });
        }

        $histories = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('histories.index', compact('histories', 'search'));
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

            // Acceso Vascular 2
            'tipo2'                => 'nullable|in:CVC TUNELIZADO,CVC TEMPORAL,FAV,INJERTO',
            'localizacion2'        => 'nullable|in:RADIAL,HUMERAL,CERVICAL,FEMORAL,OTROS',
            'lado2'                => 'nullable|in:DERECHA,IZQUIERDA',

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

        return redirect()->route('histories.edit', $history->order_id)
            ->with('success', 'Ficha clínica de hemodiálisis y antecedentes tricolumna actualizados correctamente.');
    }
}