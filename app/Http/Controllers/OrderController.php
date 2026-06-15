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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // 1. Iniciamos la consulta base con sus relaciones cargadas
        $query = Order::with(['patient', 'user']);

        // 2. Filtro de Fecha: Si viene una fecha se usa, si no, se filtra por el día de hoy por defecto
        if ($request->filled('fecha_filtro')) {
            $query->whereDate('fecha', $request->fecha_filtro);
        } else {
            // Si el usuario no ha enviado un filtro de fecha aún (por ejemplo, al entrar por primera vez)
            $query->whereDate('fecha', Carbon::today());
        }

        // 3. Filtro por Nombres
        if ($request->filled('paciente_nombre')) {
            $nombreBusqueda = $request->paciente_nombre;
            $query->whereHas('patient', function ($q) use ($nombreBusqueda) {
                $q->where('nombre', 'like', '%' . $nombreBusqueda . '%'); // Ajusta 'apellido' si cambia en tu BD
            });
        }

        // 4. Filtro por DNI del Paciente
        if ($request->filled('paciente_dni')) {
            $dniBusqueda = $request->paciente_dni;
            $query->whereHas('patient', function ($q) use ($dniBusqueda) {
                $q->where('dni', 'like', '%' . $dniBusqueda . '%'); // Ajusta 'dni' si cambia en tu BD
            });
        }

        // 5. Filtro por tipo de orden
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // 6. Filtro por estado de la orden
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 7. Paginación configurada a 15 registros por página conservando el orden descendente
        $orders = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // Retornamos la vista pasando también los filtros aplicados para mantenerlos en los inputs de la vista
        return view('orders.index', compact('orders', 'request'));
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