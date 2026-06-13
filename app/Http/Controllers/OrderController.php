<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Patient;
use App\Models\History;
use App\Models\Medical;
use App\Models\Nurse;
use App\Models\Treatment;
use App\Models\Laboratory; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $patients = Patient::orderBy('nombre', 'asc')->get(); 
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

        DB::beginTransaction();

        try {
            $order = Order::create([
                'patient_id' => $request->patient_id,
                'user_id' => Auth::id(), 
                'fecha' => $request->fecha,
                'codigo' => $codigo,
                'tipo' => $request->tipo,
                'estado' => $request->estado,
                'observaciones' => $request->observaciones,
            ]);

            if ($request->tipo === 'HISTORIA') {
                History::create([
                    'order_id'         => $order->id,
                    'patient_id'       => $order->patient_id,
                    'user_id'          => Auth::id(),
                    'fecha_ingreso_hd' => $order->fecha,
                ]);

            } elseif ($request->tipo === 'HEMODIALISIS') {
                
                Medical::create([
                    'order_id'      => $order->id,
                    'patient_id'    => $order->patient_id,
                    'user_id'       => Auth::id(),
                    'numero_sesion' => 'PENDIENTE',
                    'fecha_sesion'  => $order->fecha,
                    'qb'            => 0, 
                    'qd'            => 0, 
                    'tiempo_horas'  => 0,
                    'cama'          => null,
                    'evaluacion'    => null,
                ]);

                $horaActual = now()->format('H:i:s');
                Nurse::create([
                    'order_id'        => $order->id,
                    'patient_id'      => $order->patient_id,
                    'user_id'         => Auth::id(),
                    'hora1'           => $horaActual,
                    's_subjetivo'     => 'Pendiente de registro',
                    'hora2'           => $horaActual,
                    'o_objetivo'      => 'Pendiente de registro',
                    'hora3'           => $horaActual,
                    'a_analisis'      => 'Pendiente de registro',
                    'hora4'           => $horaActual,
                    'p_planificacion' => 'Pendiente de registro',
                    'hora5'           => $horaActual,
                    'i_intervencion'  => 'Pendiente de registro',
                    'hora6'           => $horaActual,
                    'e_evaluacion'    => 'Pendiente de registro',
                ]);

                Treatment::create([
                    'order_id'   => $order->id,
                    'patient_id' => $order->patient_id,
                    'user_id'    => Auth::id(),
                    'hora'       => $horaActual,
                    'ptm'        => 0,
                    'pa'         => null,
                    'fc'         => null,
                    'sao2'       => null,
                ]);
            } elseif ($request->tipo === 'LABORATORIO') {
                Laboratory::create([
                    'order_id'   => $order->id,
                    'patient_id' => $order->patient_id,
                    'user_id'    => Auth::id(),
                    'fecha'      => $order->fecha,
                    'tipo'       => 'General / Control',
                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Orden guardada y submódulos clínicos inicializados.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al procesar la orden: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $patients = Patient::orderBy('nombre', 'asc')->get();
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