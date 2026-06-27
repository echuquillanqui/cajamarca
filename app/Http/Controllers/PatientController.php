<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Patient;
use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $departamentos = Departamento::orderBy('descripcion')->get();
        $provincias = Provincia::orderBy('descripcion')->get();
        $distritos = Distrito::orderBy('descripcion')->get();

        $departamentosPorId = $departamentos->keyBy('id_departamento');
        $provinciasPorUbigeo = $provincias->keyBy(fn (Provincia $provincia) => $provincia->id_departamento . '-' . $provincia->id_provincia);
        $distritosPorUbigeo = $distritos->keyBy(fn (Distrito $distrito) => $distrito->id_departamento . '-' . $distrito->id_provincia . '-' . $distrito->id_distrito);

        // Traemos todos los registros para mandarlos al JSON de Alpine.js
        $patients = Patient::latest()->get()->each(function (Patient $patient) use ($departamentosPorId, $provinciasPorUbigeo, $distritosPorUbigeo) {
            $patient->setRelation('departamento', $departamentosPorId->get($patient->id_departamento));
            $patient->setRelation('provincia', $provinciasPorUbigeo->get($patient->id_departamento . '-' . $patient->id_provincia));
            $patient->setRelation('distrito', $distritosPorUbigeo->get($patient->id_departamento . '-' . $patient->id_provincia . '-' . $patient->id_distrito));
        });

        return view('patients.index', compact('patients', 'departamentos', 'provincias', 'distritos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'dni' => 'required|string|max:15|unique:patients,dni',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'telefono' => 'nullable|string|max:20',
            'procedencia' => 'nullable|string|max:100',
            'id_departamento' => ['nullable', 'string', 'size:2', Rule::exists('departamentos', 'id_departamento')],
            'id_provincia' => ['nullable', 'required_with:id_departamento', 'string', 'size:2', Rule::exists('provincias', 'id_provincia')->where('id_departamento', $request->input('id_departamento'))],
            'id_distrito' => ['nullable', 'required_with:id_provincia', 'string', 'size:2', Rule::exists('distritos', 'id_distrito')->where('id_departamento', $request->input('id_departamento'))->where('id_provincia', $request->input('id_provincia'))],
            'direccion' => 'nullable|string|max:255',
            'instruccion' => 'nullable|string|max:255',
            'civil' => 'nullable|string|max:255',
            'financiador' => 'nullable|string|max:50',
            'codigo_seguro' => 'nullable|string|max:50',
            'contacto_emergencia_nombre' => 'nullable|string|max:150',
            'contacto_emergencia_dni' => 'nullable|string|max:15',
            'contacto_emergencia_parentesco' => 'nullable|string|max:50',
            'contacto_emergencia_telefono' => 'nullable|string|max:20',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Paciente registrado correctamente en el sistema.');
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'dni' => 'required|string|max:15|unique:patients,dni,' . $patient->id,
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'telefono' => 'nullable|string|max:20',
            'procedencia' => 'nullable|string|max:100',
            'id_departamento' => ['nullable', 'string', 'size:2', Rule::exists('departamentos', 'id_departamento')],
            'id_provincia' => ['nullable', 'required_with:id_departamento', 'string', 'size:2', Rule::exists('provincias', 'id_provincia')->where('id_departamento', $request->input('id_departamento'))],
            'id_distrito' => ['nullable', 'required_with:id_provincia', 'string', 'size:2', Rule::exists('distritos', 'id_distrito')->where('id_departamento', $request->input('id_departamento'))->where('id_provincia', $request->input('id_provincia'))],
            'direccion' => 'nullable|string|max:255',
            'instruccion' => 'nullable|string|max:255',
            'civil' => 'nullable|string|max:255',
            'financiador' => 'nullable|string|max:50',
            'codigo_seguro' => 'nullable|string|max:50',
            'contacto_emergencia_nombre' => 'nullable|string|max:150',
            'contacto_emergencia_dni' => 'nullable|string|max:15',
            'contacto_emergencia_parentesco' => 'nullable|string|max:50',
            'contacto_emergencia_telefono' => 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Los datos del paciente han sido actualizados.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Paciente removido de la base de datos.');
    }
}