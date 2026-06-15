<?php

namespace App\Http\Controllers;

use App\Models\Nurse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NurseController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $fechaFiltro = $request->input('fecha_filtro', Carbon::today()->format('Y-m-d'));
        $query = Nurse::with(['order', 'patient', 'user'])->latest();

        if ($request->filled('fecha_filtro')) {
            $query->whereHas('order', function ($o) use ($fechaFiltro) {
                $o->whereDate('fecha', $fechaFiltro);
            });
        } else {
            $query->whereHas('order', function ($o) {
                $o->whereDate('fecha', Carbon::today());
            });
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($p) use ($search) {
                    $p->where('nombre', 'LIKE', '%' . $search . '%')
                        ->orWhere('dni', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('order', function ($o) use ($search) {
                    $o->where('codigo', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('s_subjetivo', 'LIKE', '%' . $search . '%')
                ->orWhere('o_objetivo', 'LIKE', '%' . $search . '%')
                ->orWhere('uf_efectivo', 'LIKE', '%' . $search . '%')
                ->orWhere('asp_filtro', 'LIKE', '%' . $search . '%');
            });
        }

        $nurses = $query->paginate(10)->withQueryString();
        return view('nurses.index', compact('nurses', 'search', 'fechaFiltro'));
    }

    public function edit(Nurse $nurse)
    {
        return view('nurses.edit', compact('nurse'));
    }

    public function update(Request $request, Nurse $nurse)
    {
        $validatedData = $request->validate([
            'hora1'       => 'required',
            's_subjetivo' => 'required|string',
            'hora2'       => 'required',
            'o_objetivo'  => 'required|string',
            'hora3'       => 'required',
            'a_analisis'  => 'required|string',
            'hora4'       => 'required',
            'p_planificacion' => 'required|string',
            'hora5'       => 'required',
            'i_intervencion'  => 'required|string',
            'hora6'       => 'required',
            'e_evaluacion'    => 'required|string',
            'uf_efectivo' => 'nullable|string|max:100',
            'asp_filtro'  => 'nullable|string|max:255',
            'epo'         => 'nullable|string|max:255',
            'hierro'      => 'nullable|string|max:255',
            'vitb12'      => 'nullable|string|max:255',
        ]);

        $nurse->update($validatedData);

        return redirect()->route('orders.show', $nurse->order_id)
            ->with('success', 'Registro de enfermería SOAPIE actualizado.');
    }

    public function destroy(Nurse $nurse)
    {
        $orderId = $nurse->order_id;
        $nurse->delete();

        return redirect()->route('orders.show', $orderId)
            ->with('success', 'La hoja SOAPIE ha sido eliminada con éxito.');
    }
}