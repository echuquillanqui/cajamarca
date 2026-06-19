<?php

namespace App\Exports;

use App\Exports\Reports\ArraySheetExport;
use App\Models\History;
use App\Models\Laboratory;
use App\Models\Medical;
use App\Models\Nurse;
use App\Models\Order;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ClinicalReportsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(private readonly array $filters = [])
    {
    }

    public function sheets(): array
    {
        $orderIds = $this->filteredOrdersQuery()->pluck('orders.id');

        return [
            new ArraySheetExport('Historia inicial', $this->historyHeadings(), $this->historyRows($orderIds)),
            new ArraySheetExport('Medicals', $this->medicalHeadings(), $this->medicalRows($orderIds)),
            new ArraySheetExport('Nurses', $this->nurseHeadings(), $this->nurseRows($orderIds)),
            new ArraySheetExport('Treatment', $this->treatmentHeadings(), $this->treatmentRows($orderIds)),
            new ArraySheetExport('Laboratories', $this->laboratoryHeadings(), $this->laboratoryRows($orderIds)),
            new ArraySheetExport('Completo', $this->completeHeadings(), $this->completeRows($orderIds)),
        ];
    }

    private function filteredOrdersQuery(): Builder
    {
        $query = Order::query()->with(['patient', 'user']);

        if (! empty($this->filters['fecha_filtro'])) {
            $query->whereDate('fecha', $this->filters['fecha_filtro']);
        }

        if (! empty($this->filters['paciente_nombre'])) {
            $query->whereHas('patient', fn (Builder $q) => $q->where('nombre', 'like', '%' . $this->filters['paciente_nombre'] . '%'));
        }

        if (! empty($this->filters['paciente_dni'])) {
            $query->whereHas('patient', fn (Builder $q) => $q->where('dni', 'like', '%' . $this->filters['paciente_dni'] . '%'));
        }

        if (! empty($this->filters['tipo'])) {
            $query->where('tipo', $this->filters['tipo']);
        }

        if (! empty($this->filters['estado'])) {
            $query->where('estado', $this->filters['estado']);
        }

        return $query->orderByDesc('id');
    }

    private function baseColumns($record): array
    {
        return [
            $record->order?->codigo,
            $record->order?->fecha?->format('d/m/Y'),
            $record->order?->tipo,
            $record->order?->estado,
            $record->patient?->nombre,
            $record->patient?->dni,
            $record->user?->name,
        ];
    }

    private function formatJson(mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;
    }

    private function commonHeadings(): array
    {
        return ['Código orden', 'Fecha orden', 'Tipo orden', 'Estado orden', 'Paciente', 'DNI', 'Responsable'];
    }

    private function historyHeadings(): array
    {
        return array_merge($this->commonHeadings(), ['Fecha ingreso HD', 'Servicio origen', 'Cama', 'Tiempo enfermedad', 'Inicio enfermedad', 'Curso enfermedad', 'Relato cronológico', 'Antecedentes personales', 'Antecedentes familiares', 'Alergias', 'PA', 'FC', 'FR', 'Sat O2', 'Peso ingreso', 'Talla ingreso', 'Diagnóstico', 'Fecha alta', 'Pendientes']);
    }

    private function historyRows(Collection $orderIds): Collection
    {
        return History::with(['order', 'patient', 'user'])->whereIn('order_id', $orderIds)->get()->map(fn (History $history) => array_merge($this->baseColumns($history), [
            $history->fecha_ingreso_hd?->format('d/m/Y'), $history->serv_origen, $history->cama, $history->tiempo_enfermedad, $history->inicio_enfermedad, $history->curso_enfermedad, $history->relato_cronologico, $this->formatJson($history->antecedentes_personales), $history->antecedentes_familiares, $history->alergias, $history->pa, $history->fc, $history->fr, $history->sat_o2, $history->peso_ingreso, $history->talla_ingreso, $this->formatJson($history->diagnostico), $history->f_alta?->format('d/m/Y'), $history->pendientes,
        ]));
    }

    private function medicalHeadings(): array
    {
        return array_merge($this->commonHeadings(), ['N° sesión', 'Fecha sesión', 'Servicio procedencia', 'Cama', 'PA', 'FC', 'FR', 'Sat', 'Evaluación', 'Peso seco', 'Diuresis', 'Alergias', 'Alergias descripción', 'Técnica', 'Frecuencia', 'Acceso', 'Heparina', 'Filtro', 'Membrana', 'QB', 'QD', 'Tiempo horas', 'Otras indicaciones', 'Grado dep.']);
    }

    private function medicalRows(Collection $orderIds): Collection
    {
        return Medical::with(['order', 'patient', 'user'])->whereIn('order_id', $orderIds)->get()->map(fn (Medical $medical) => array_merge($this->baseColumns($medical), [$medical->numero_sesion, $medical->fecha_sesion?->format('d/m/Y'), $medical->servicio_procedencia, $medical->cama, $medical->pa, $medical->fc, $medical->fr, $medical->sat, $medical->evaluacion, $medical->peso_seco, $medical->diuresis, $medical->alergias ? 'Sí' : 'No', $medical->alergias_descripcion, $medical->tecnica, $medical->frecuencia, $medical->acceso, $medical->heparina, $medical->filtro, $medical->membrana, $medical->qb, $medical->qd, $medical->tiempo_horas, $medical->otras_indicaciones, $medical->grado_dep]));
    }

    private function nurseHeadings(): array { return array_merge($this->commonHeadings(), ['Hora S', 'Subjetivo', 'Hora O', 'Objetivo', 'Hora A', 'Análisis', 'Hora P', 'Planificación', 'Hora I', 'Intervención', 'Hora E', 'Evaluación', 'UF efectivo', 'ASP filtro', 'EPO', 'Hierro', 'Vit B12']); }
    private function nurseRows(Collection $orderIds): Collection { return Nurse::with(['order', 'patient', 'user'])->whereIn('order_id', $orderIds)->get()->map(fn (Nurse $nurse) => array_merge($this->baseColumns($nurse), [$nurse->hora1, $nurse->s_subjetivo, $nurse->hora2, $nurse->o_objetivo, $nurse->hora3, $nurse->a_analisis, $nurse->hora4, $nurse->p_planificacion, $nurse->hora5, $nurse->i_intervencion, $nurse->hora6, $nurse->e_evaluacion, $nurse->uf_efectivo, $nurse->asp_filtro, $nurse->epo, $nurse->hierro, $nurse->vitb12])); }

    private function treatmentHeadings(): array { return array_merge($this->commonHeadings(), ['Hora', 'PA', 'PAM', 'FC', 'SaO2', 'UF hora', 'Sodio', 'QB', 'RA', 'RV', 'PTM', 'Observaciones', 'Laboratorio control']); }
    private function treatmentRows(Collection $orderIds): Collection { return Treatment::with(['order', 'patient', 'user'])->whereIn('order_id', $orderIds)->get()->map(fn (Treatment $treatment) => array_merge($this->baseColumns($treatment), [$treatment->hora, $treatment->pa, $treatment->pam, $treatment->fc, $treatment->sao2, $treatment->uf_hora, $treatment->sodio, $treatment->qb, $treatment->ra, $treatment->rv, $treatment->ptm, $treatment->observaciones, $treatment->laboratorio_control])); }

    private function laboratoryHeadings(): array { return array_merge($this->commonHeadings(), ['Fecha laboratorio', 'Tipo', 'Resultados', 'Observaciones']); }
    private function laboratoryRows(Collection $orderIds): Collection { return Laboratory::with(['order', 'patient', 'user'])->whereIn('order_id', $orderIds)->get()->map(fn (Laboratory $laboratory) => array_merge($this->baseColumns($laboratory), [$laboratory->fecha?->format('d/m/Y'), $laboratory->tipo, $this->formatJson($laboratory->resultados), $laboratory->observaciones])); }

    private function completeHeadings(): array { return ['Código', 'Fecha', 'Tipo', 'Estado', 'Paciente', 'DNI', 'Responsable', 'Observaciones', 'Historias', 'Medicals', 'Nurses', 'Treatments', 'Laboratories']; }
    private function completeRows(Collection $orderIds): Collection
    {
        return Order::with(['patient', 'user', 'histories', 'medicals', 'nurses', 'treatments', 'laboratories'])->whereIn('id', $orderIds)->orderByDesc('id')->get()->map(fn (Order $order) => [$order->codigo, $order->fecha?->format('d/m/Y'), $order->tipo, $order->estado, $order->patient?->nombre, $order->patient?->dni, $order->user?->name, $order->observaciones, $order->histories->count(), $order->medicals->count(), $order->nurses->count(), $order->treatments->count(), $order->laboratories->count()]);
    }
}
