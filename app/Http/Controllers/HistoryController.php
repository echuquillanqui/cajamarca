<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Order;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Paginación del lado del servidor combinada con la interactividad de la tabla
        $histories = History::with(['patient', 'user'])->latest()->paginate(10);
        return view('histories.index', compact('histories'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nombre')->get();
        $orders = Order::where('estado', 'PENDIENTE')->orderByDesc('fecha')->get();

        return view('histories.create', compact('patients', 'orders'));
    }

    public function store(Request $request)
    {
        $this->prepareJsonFields($request);

        $validated = $request->validate($this->validationRules());
        $validated = $this->normalizeCheckboxes($request, $validated);
        $validated['user_id'] = Auth::id();

        History::create($validated);

        return redirect()->route('histories.index')->with('success', 'Historia Clínica registrada correctamente.');
    }

    public function show(History $history)
    {
        $history->load(['patient', 'user']);
        return view('histories.show', compact('history'));
    }

    public function edit(History $history)
    {
        $patients = Patient::orderBy('nombre')->get();
        $orders = Order::where('estado', 'PENDIENTE')
            ->orWhere('id', $history->order_id)
            ->orderByDesc('fecha')
            ->get();

        return view('histories.edit', compact('history', 'patients', 'orders'));
    }

    public function update(Request $request, History $history)
    {
        $this->prepareJsonFields($request);

        $validated = $request->validate($this->validationRules());
        $validated = $this->normalizeCheckboxes($request, $validated);

        $history->update($validated);

        return redirect()->route('histories.index')->with('success', 'Historia Clínica actualizada con éxito.');
    }

    public function destroy(History $history)
    {
        $history->delete();
        return redirect()->route('histories.index')->with('success', 'El expediente clínico ha sido eliminado del sistema.');
    }

    private function validationRules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'patient_id' => 'required|exists:patients,id',
            'fecha_ingreso_hd' => 'required|date',
            'serv_origen' => 'nullable|string|max:25',
            'tiempo_enfermedad' => 'nullable|string|max:50',
            'inicio_enfermedad' => 'nullable|string|max:50',
            'curso_enfermedad' => 'nullable|string|max:50',
            'relato_cronologico' => 'nullable|string',
            'apetito' => 'nullable|string|max:30',
            'sed' => 'nullable|string|max:30',
            'heces' => 'nullable|string|max:30',
            'sueno' => 'nullable|string|max:30',
            'diuresis_ingreso' => 'nullable|string|max:50',
            'antecedentes_personales' => 'nullable|array',
            'antecedentes_familiares' => 'nullable|string',
            'alergias' => 'nullable|string',
            'biopsia_renal' => 'nullable|boolean',
            'biopsia_renal_anio' => 'nullable|string|max:4',
            'biopsia_renal_resultado' => 'nullable|string|max:255',
            'pa' => 'nullable|string|max:15',
            'fc' => 'nullable|integer',
            'fr' => 'nullable|integer',
            'sat_o2' => 'nullable|integer',
            'peso_ingreso' => 'nullable|numeric|between:0,999.99',
            'talla_ingreso' => 'nullable|numeric|between:0,9.99',
            'fio' => 'nullable|numeric|between:0,999.99',
            'aspecto_general' => 'nullable|string',
            'piel' => 'nullable|string',
            'tcsc' => 'nullable|string',
            'respiratorio' => 'nullable|string',
            'cardiovascular' => 'nullable|string',
            'tipo' => 'nullable|in:CVC TUNELIZADO,CVC TEMPORAL,FAV,INJERTO',
            'tipo2' => 'nullable|in:CVC TUNELIZADO,CVC TEMPORAL,FAV,INJERTO',
            'localizacion' => 'nullable|in:RADIAL,HUMERAL,CERVICAL,FEMORAL,OTROS',
            'localizacion2' => 'nullable|in:RADIAL,HUMERAL,CERVICAL,FEMORAL,OTROS',
            'lado' => 'nullable|in:DERECHA,IZQUIERDA',
            'lado2' => 'nullable|in:DERECHA,IZQUIERDA',
            'estado' => 'nullable|in:BUENO,MALO,REGULAR',
            'd_peritoneal' => 'nullable|boolean',
            't_renal' => 'nullable|boolean',
            'o_tipos' => 'nullable|string|max:50',
            'o_fecha' => 'nullable|date',
            'o_causa' => 'nullable|string|max:100',
            'abdomen' => 'nullable|string|max:100',
            'g_urinario' => 'nullable|string|max:100',
            'neurologico' => 'nullable|string|max:100',
            'e_nutricional' => 'nullable|string|max:100',
            'hiv' => 'nullable|boolean',
            'hbsag' => 'nullable|boolean',
            'anti_hbc' => 'nullable|boolean',
            'vhc' => 'nullable|boolean',
            'anti_hbs' => 'nullable|boolean',
            'rpr' => 'nullable|boolean',
            'ningun_se' => 'nullable|string|max:50',
            'vacuna_ingreso' => 'nullable|integer',
            'vacuna_alta' => 'nullable|integer',
            'otras_vacunas' => 'nullable|string|max:200',
            'enf_cronica' => 'nullable|in:G,A',
            'descrip1' => 'nullable|string|max:50',
            'etiologia_cronica' => 'nullable|string|max:200',
            'enf_aguda' => 'nullable|in:1,2,3',
            'descrip2' => 'nullable|string|max:50',
            'etiologia_aguda' => 'nullable|string|max:200',
            'motivo_hospt_act' => 'nullable|string',
            'diagnostico' => 'nullable|array',
            'f_alta' => 'nullable|date',
            'consideraciones_alta' => 'nullable|string|max:255',
            'motivo_fallece' => 'nullable|string',
            'pendientes' => 'nullable|string',
            'peso_seco' => 'nullable|numeric|between:0,999999.99',
            'diuresis_alta' => 'nullable|string|max:50',
        ];
    }

    private function prepareJsonFields(Request $request): void
    {
        foreach (['antecedentes_personales', 'diagnostico'] as $field) {
            $value = $request->input($field);

            if (is_string($value) && $value !== '') {
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decoded]);
                }
            }
        }
    }

    private function normalizeCheckboxes(Request $request, array $validated): array
    {
        $checkboxes = ['biopsia_renal', 'd_peritoneal', 't_renal', 'hiv', 'hbsag', 'anti_hbc', 'vhc', 'anti_hbs', 'rpr'];

        foreach ($checkboxes as $checkbox) {
            $validated[$checkbox] = $request->boolean($checkbox);
        }

        return $validated;
    }
}
