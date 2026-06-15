<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\Order; // Importación necesaria para buscar la orden
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $query = Order::with([
            'patient',
            'treatments' => function ($treatments) {
                $treatments->with('user')->orderBy('hora', 'asc');
            },
        ])
            ->whereHas('treatments')
            ->withCount('treatments')
            ->latest('updated_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($p) use ($search) {
                    $p->where('nombre', 'LIKE', '%' . $search . '%')
                        ->orWhere('dni', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('codigo', 'LIKE', '%' . $search . '%')
                ->orWhereHas('treatments', function ($t) use ($search) {
                    $t->where('pa', 'LIKE', '%' . $search . '%')
                        ->orWhere('observaciones', 'LIKE', '%' . $search . '%');
                });
            });
        }

        $treatmentOrders = $query->paginate(10)->withQueryString();

        return view('treatments.index', compact('treatmentOrders', 'search'));
    }

    public function edit($order_id)
    {
        $treatments = Treatment::where('order_id', $order_id)->orderBy('hora', 'asc')->get();

        $treatments_json = $treatments->map(function ($t) {
            return [
                'id' => $t->id,
                'hora' => \Carbon\Carbon::parse($t->hora)->format('H:i'),
                'pa' => $t->pa,
                'pam' => $t->pam,
                'fc' => $t->fc,
                'sao2' => $t->sao2,
                'uf_hora' => $t->uf_hora,
                'sodio' => $t->sodio,
                'qb' => $t->qb,
                'ra' => $t->ra,
                'rv' => $t->rv,
                'ptm' => $t->ptm,
                'laboratorio_control' => $t->laboratorio_control,
                'observaciones' => $t->observaciones,
            ];
        });

        return view('treatments.edit', compact('order_id', 'treatments_json'));
    }

    public function update(Request $request, $order_id)
    {
        $data = $request->validate([
            'treatments' => 'required|array',
            'treatments.*.id' => 'nullable',
            'treatments.*.hora' => 'required',
            'treatments.*.ptm' => 'required|integer',
        ]);

        // Buscamos la orden maestra para obtener el paciente correcto de forma segura
        $order = Order::findOrFail($order_id);

        $sentIds = collect($request->treatments)->pluck('id')->filter()->toArray();
        Treatment::where('order_id', $order_id)->whereNotIn('id', $sentIds)->delete();

        foreach ($request->treatments as $treatmentData) {
            Treatment::updateOrCreate(
                ['id' => $treatmentData['id'] ?? null],
                [
                    'order_id' => $order_id,
                    'patient_id' => $order->patient_id, // Asignación corregida y segura
                    'user_id' => auth()->id(),
                    'hora' => $treatmentData['hora'],
                    'pa' => $treatmentData['pa'] ?? null,
                    'pam' => $treatmentData['pam'] ?? null,
                    'fc' => $treatmentData['fc'] ?? null,
                    'sao2' => $treatmentData['sao2'] ?? null,
                    'uf_hora' => $treatmentData['uf_hora'] ?? null,
                    'sodio' => $treatmentData['sodio'] ?? null,
                    'qb' => $treatmentData['qb'] ?? null,
                    'ra' => $treatmentData['ra'] ?? null,
                    'rv' => $treatmentData['rv'] ?? null,
                    'ptm' => $treatmentData['ptm'],
                    'laboratorio_control' => $treatmentData['laboratorio_control'] ?? null,
                    'observaciones' => $treatmentData['observaciones'] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Sábana de monitoreo actualizada con éxito.');
    }

    public function destroy(Treatment $treatment)
    {
        $orderId = $treatment->order_id;
        $treatment->delete();

        return redirect()->route('orders.show', $orderId)
            ->with('success', 'La fila de monitoreo horario ha sido removida.');
    }
}