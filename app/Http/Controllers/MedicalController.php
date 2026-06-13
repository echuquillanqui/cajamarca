<?php

namespace App\Http\Controllers;

use App\Models\Medical;
use Illuminate\Http\Request;

class MedicalController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $query = Medical::with(['order', 'patient', 'user'])->latest();

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
                ->orWhere('numero_sesion', 'LIKE', '%' . $search . '%');
            });
        }

        $medicals = $query->paginate(10)->withQueryString();
        return view('medicals.index', compact('medicals', 'search'));
    }

    public function edit(Medical $medical)
    {
        return view('medicals.edit', compact('medical'));
    }

    public function update(Request $request, Medical $medical)
    {
        $validatedData = $request->validate([
            'numero_sesion'        => 'nullable|string|max:20',
            'fecha_sesion'         => 'required|date',
            'servicio_procedencia' => 'nullable|string|max:50',
            'cama'                 => 'nullable|string|max:10',

            // EVALUACION CLINICA
            'pa'                   => 'nullable|string|max:15',
            'fc'                   => 'nullable|string|max:10',
            'fr'                   => 'nullable|string|max:10',
            'sat'                  => 'nullable|string|max:10',
            'evaluacion'           => 'nullable|string',
            'peso_seco'            => 'nullable|numeric|between:0,999.99',
            'diuresis'             => 'nullable|string|max:50',
            'alergias'             => 'required|boolean',
            'alergias_descripcion' => 'nullable|string',

            // PRESCRIPCION
            'tecnica'              => 'nullable|string|max:50',
            'frecuencia'           => 'nullable|string|max:30',
            'acceso'               => 'nullable|string|max:50',
            'heparina'             => 'nullable|string|max:50',
            'filtro'               => 'nullable|string|max:30',
            'membrana'             => 'nullable|string|max:30',
            'qb'                   => 'nullable|integer|min:0',
            'qd'                   => 'nullable|integer|min:0',
            'tiempo_horas'         => 'nullable|integer|min:0',
            'sodio_mEq'            => 'nullable|integer|min:0',
            'perfil_sodio'         => 'nullable|string|max:30',
            'tdld'                 => 'nullable|string|max:30',
            'uft'                  => 'nullable|string|max:30',
            'uf_asilada'           => 'nullable|string|max:30',
            'perfil_uf'            => 'nullable|string|max:30',
            'uf_efectivo'          => 'nullable|string|max:30',
            'otras_indicaciones'   => 'nullable|string',
            'grado_dep'            => 'nullable|in:I,II,III,IV',
            'grup_fact'            => 'nullable|string|max:30',
            'transfuciones'        => 'required|boolean',
            't_inicial'            => 'nullable|string|max:255',
            't_final'              => 'nullable|string|max:255',
            'p_inicial'            => 'nullable|string|max:255',
            'p_final'              => 'nullable|string|max:255',
        ]);

        $medical->update($validatedData);

        return redirect()->route('medicals.edit', $medical->id)->with('success', 'La orden médica ha sido actualizada correctamente.');
    }
}