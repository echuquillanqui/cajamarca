<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = Order::with(['patient', 'user'])->orderBy('id', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nombre', 'asc')->get(); // Cambiar 'name' si tu columna es diferente
        return view('orders.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'fecha' => 'required|date',
            'codigo' => 'nullable|string|max:50|unique:orders,codigo',
            'tipo' => 'required|in:HISTORIA,HEMODIALISIS,LABORATORIO',
            'estado' => 'required|in:PENDIENTE,EN_PROCESO,FINALIZADA,ANULADA',
            'observaciones' => 'nullable|string',
        ]);

        $codigo = $request->codigo ?? 'ORD-' . strtoupper(uniqid());

        Order::create([
            'patient_id' => $request->patient_id,
            'user_id' => Auth::id(), 
            'fecha' => $request->fecha,
            'codigo' => $codigo,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('orders.index')->with('success', 'Orden guardada correctamente.');
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $patients = Patient::orderBy('name', 'asc')->get();
        return view('orders.edit', compact('order', 'patients'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'fecha' => 'required|date',
            'codigo' => 'required|string|max:50|unique:orders,codigo,' . $order->id,
            'tipo' => 'required|in:HISTORIA,HEMODIALISIS,LABORATORIO',
            'estado' => 'required|in:PENDIENTE,EN_PROCESO,FINALIZADA,ANULADA',
            'observaciones' => 'nullable|string',
        ]);

        $order->update([
            'patient_id' => $request->patient_id,
            'fecha' => $request->fecha,
            'codigo' => $request->codigo,
            'tipo' => $request->tipo,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('orders.index')->with('success', 'Orden actualizada correctamente.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Orden eliminada con éxito.');
    }
}