<?php

namespace App\Http\Controllers;

use App\Exports\ClinicalReportsExport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $filters = $this->filters($request);
        $orders = $this->ordersQuery($filters)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('reports.index', compact('orders', 'filters'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $this->filters($request);
        $filename = 'reporte-clinico-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new ClinicalReportsExport($filters), $filename);
    }

    private function filters(Request $request): array
    {
        return [
            'fecha_filtro' => $request->input('fecha_filtro', Carbon::today()->format('Y-m-d')),
            'paciente_nombre' => $request->input('paciente_nombre'),
            'paciente_dni' => $request->input('paciente_dni'),
            'tipo' => $request->input('tipo'),
            'estado' => $request->input('estado'),
        ];
    }

    private function ordersQuery(array $filters): Builder
    {
        $query = Order::with(['patient', 'user', 'histories', 'medicals', 'nurses', 'treatments', 'laboratories']);

        if (! empty($filters['fecha_filtro'])) {
            $query->whereDate('fecha', $filters['fecha_filtro']);
        }

        if (! empty($filters['paciente_nombre'])) {
            $query->whereHas('patient', function (Builder $query) use ($filters) {
                $query->where('nombre', 'like', '%' . $filters['paciente_nombre'] . '%');
            });
        }

        if (! empty($filters['paciente_dni'])) {
            $query->whereHas('patient', function (Builder $query) use ($filters) {
                $query->where('dni', 'like', '%' . $filters['paciente_dni'] . '%');
            });
        }

        if (! empty($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }

        if (! empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        return $query;
    }
}
