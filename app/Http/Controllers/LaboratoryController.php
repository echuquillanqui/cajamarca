<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        // Cargamos todas las relaciones necesarias para evitar el problema de consultas N+1
        $laboratories = Laboratory::with(['patient', 'order', 'user'])
            ->whereDate('fecha', $date)
            ->orderBy('fecha', 'desc')
            ->get(); // Traemos la colección para que Alpine la filtre de forma instantánea en tiempo real

        return view('laboratories.index', compact('laboratories', 'date'));
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
        return view('laboratories.edit', compact('laboratory'));
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