<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Cargamos todas las relaciones necesarias para evitar el problema de consultas N+1
        $query = Laboratory::with(['patient', 'order', 'user']);

        if ($dateFrom || $dateTo) {
            if ($dateFrom) {
                $query->whereDate('fecha', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('fecha', '<=', $dateTo);
            }
        } else {
            $query->whereDate('fecha', $date);
        }

        $laboratories = $query
            ->orderBy('fecha', 'desc')
            ->get(); // Traemos la colección para que Alpine la filtre de forma instantánea en tiempo real

        return view('laboratories.index', compact('laboratories', 'date', 'dateFrom', 'dateTo'));
    }


    public function pdf(Laboratory $laboratory)
    {
        $laboratory->load(['patient', 'order', 'user']);

        $pdf = Pdf::loadView('laboratories.pdf', [
            'laboratories' => collect([$laboratory]),
            'examsByCategory' => self::examsByCategory(),
            'title' => 'MONITOREO DE LABORATORIO DE PACIENTES EN HEMODIÁLISIS',
            'subtitle' => 'CONTROL INDIVIDUAL',
            'dateFrom' => $laboratory->fecha?->format('Y-m-d'),
            'dateTo' => $laboratory->fecha?->format('Y-m-d'),
        ])->setPaper('a4', 'landscape')->setWarnings(false);

        $filename = 'LABORATORIO-' . ($laboratory->order->codigo ?? $laboratory->id) . '-' . ($laboratory->patient?->dni ?? 'paciente') . '.pdf';

        return $pdf->stream($filename);
    }

    public function rangePdf(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $laboratories = Laboratory::with(['patient', 'order', 'user'])
            ->whereDate('fecha', '>=', $validated['date_from'])
            ->whereDate('fecha', '<=', $validated['date_to'])
            ->orderBy('fecha')
            ->orderBy('patient_id')
            ->get();

        $pdf = Pdf::loadView('laboratories.pdf', [
            'laboratories' => $laboratories,
            'examsByCategory' => self::examsByCategory(),
            'title' => 'MONITOREO DE LABORATORIO DE PACIENTES EN HEMODIÁLISIS',
            'subtitle' => 'REPORTE POR RANGO DE FECHAS',
            'dateFrom' => $validated['date_from'],
            'dateTo' => $validated['date_to'],
        ])->setPaper('a4', 'landscape')->setWarnings(false);

        return $pdf->stream('LABORATORIOS-' . $validated['date_from'] . '-AL-' . $validated['date_to'] . '.pdf');
    }

    private static function examsByCategory(): array
    {
        return [
            'FUNCIÓN RENAL' => ['UREA mg/dl', 'CREATININA mg/dl', 'PROTEINURIA 24 H', 'ALBUMINURIA 24 H', 'CKD EPI 2021'],
            'HEMOGRAMA - COAGULACIÓN - REACTANTES FASE AGUDA' => ['LEUCOCITOS', 'ABASTONADOS', 'PLAQUETAS', 'PCR', 'VSG', 'PROCALCITONINA', 'TTPa', 'TP - INR'],
            "PERFIL D'ANEMIA" => ['HEMOGLOBINA', 'HEMATOCRITO', 'FERRITINA', 'Sat. TRANSFERRINA', 'B12', 'ÁCIDO FÓLICO', 'RETICULOCITOS'],
            'MEDIO INTERNO - OXIGENACIÓN' => ['pH', 'pCO2', 'HCO3', 'K+', 'Na+', 'Cl-', 'PaO2/FiO2'],
            'METABOLISMO MINERAL ÓSEO - IONES DIVALENTES' => ['Calcio', 'Fósforo', 'PTHi', 'Fosfatasa alcalina', 'Magnesio'],
            'FUNCIÓN HEPÁTICA' => ['TGP/ALT', 'TGO/AST', 'PROTEINAS TOTALES', 'ALBUMINA', 'BILIRRUBINAS TOTALES', 'BILIRRUBINA DIRECTA'],
            'PERFIL LÍPIDICO' => ['COLESTEROL TOTAL', 'LDL', 'HDL', 'TG'],
            'AUTOINMUNIDAD' => ['ANA', 'ANCA p', 'ANCA c', 'FACTOR REUMATOIDEO', 'COOMBS DIRECTO'],
            'EXAMEN DE ORINA' => ['LEUCOCITOS (ORINA)', 'HEMATIES', 'HEMATIES DISMORFICOS', 'CILINDROS HEMATICOS', 'CILINDROS CEREOS', 'ALBUMINURIA (CUALITATIVA)', 'GLUCOSURIA'],
            'OTROS' => ['DIURESIS DIARIA'],
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratory $laboratory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laboratory $laboratory)
    {
        $laboratory->load(['patient', 'order']);

        $historyLaboratories = Laboratory::with(['order', 'user'])
            ->where('patient_id', $laboratory->patient_id)
            ->where('id', '!=', $laboratory->id)
            ->whereNotNull('resultados')
            ->whereJsonLength('resultados', '>', 0)
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laboratories.edit', compact('laboratory', 'historyLaboratories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laboratory $laboratory)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
            'resultados' => 'nullable|array',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['resultados'] = $request->input('resultados', []);

        $laboratory->update($validated);

        return redirect()
            ->route('laboratories.edit', $laboratory)
            ->with('success', 'Resultado de laboratorio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laboratory $laboratory)
    {
        $laboratory->delete();
        return redirect()
            ->route('laboratories.index')
            ->with('success', 'Registro de laboratorio eliminado con éxito.');
    }
}