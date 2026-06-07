<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Traemos todos los registros para mandarlos al JSON de Alpine.js
        $patients = Patient::latest()->get();
        return view('patients.index', compact('patients'));
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