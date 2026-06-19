<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $subtitle }}</title>
    <style>
        @page { margin: 16px 20px; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 7.6px; }
        .header { position: relative; text-align: center; border-bottom: 2px solid #222; padding-bottom: 7px; margin-bottom: 10px; }
        .brand { position: absolute; top: 0; width: 100px; font-size: 8px; font-weight: bold; color: #555; }
        .brand.left { left: 0; text-align: left; }
        .brand.right { right: 0; text-align: right; }
        .hospital { font-weight: bold; font-size: 10px; line-height: 1.25; }
        .title { margin-top: 8px; font-size: 13px; font-weight: bold; }
        .subtitle { font-size: 9px; font-weight: bold; }
        .info { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .info td { padding: 3px 5px; vertical-align: middle; }
        .label { font-weight: bold; border: 1px solid #555; background: #f2f2f2; white-space: nowrap; }
        .field { border-bottom: 1px solid #222; color: #0f4c81; font-weight: bold; min-height: 12px; }
        .grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .grid th, .grid td { border: 1px solid #555; padding: 2px 3px; text-align: center; height: 12px; }
        .grid thead th { background: #ececec; font-size: 7px; }
        .category { width: 72px; background: #f5f5f5; font-weight: bold; text-align: center; vertical-align: middle; }
        .exam { width: 118px; text-align: left !important; font-weight: bold; }
        .value { color: #0f4c81; font-weight: bold; }
        .observations { margin-top: 8px; border: 1px solid #555; padding: 6px; min-height: 24px; }
        .empty { text-align: center; padding: 40px; border: 1px solid #555; font-size: 11px; }
        .small { font-size: 7px; font-weight: normal; color: #333; }
    </style>
</head>
<body>
@php
    $firstLab = $laboratories->first();
    $patient = $firstLab?->patient;
    $date = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '';
    $rangeText = $dateFrom && $dateTo && $dateFrom !== $dateTo ? $date($dateFrom) . ' - ' . $date($dateTo) : $date($dateFrom);
    $resultValue = function ($lab, $exam) {
        foreach (($lab->resultados ?? []) as $key => $result) {
            $name = is_array($result) ? ($result['clave'] ?? '') : $key;
            if (trim(mb_strtolower($name)) === trim(mb_strtolower($exam))) {
                return is_array($result) ? ($result['valor'] ?? '') : $result;
            }
        }
        return '';
    };
@endphp

<div class="header">
    <div class="brand left">CAJAMARCA<br>MINSA</div>
    <div class="brand right">NEFROLOGÍA<br>HRDC</div>
    <div class="hospital">DIRECCIÓN REGIONAL DE SALUD DE CAJAMARCA<br>HOSPITAL REGIONAL DOCENTE DE CAJAMARCA<br>DEPARTAMENTO DE MEDICINA<br>UNIDAD DE HEMODIÁLISIS</div>
    <div class="title">{{ $title }}</div>
    <div class="subtitle">({{ $subtitle }})</div>
</div>

@if($laboratories->isEmpty())
    <div class="empty">No existen controles de laboratorio para el rango seleccionado: {{ $rangeText }}</div>
@else
    <table class="info">
        <tr>
            <td class="label" style="width: 80px;">NOMBRE</td>
            <td class="field">{{ $patient?->nombre ?? ($laboratories->count() > 1 ? 'VARIOS PACIENTES' : '') }}</td>
            <td class="label" style="width: 95px;">HISTORIA CLÍNICA</td>
            <td class="field" style="width: 120px;">{{ $patient?->dni }}</td>
        </tr>
        <tr>
            <td class="label">FECHA / RANGO</td>
            <td class="field">{{ $rangeText }}</td>
            <td class="label">ORDEN</td>
            <td class="field">{{ $firstLab?->order?->codigo ?? $firstLab?->order_id }}</td>
        </tr>
        <tr>
            <td class="label">GRUPO SANGUÍNEO</td>
            <td class="field"></td>
            <td class="label">FACTOR RH</td>
            <td class="field"></td>
        </tr>
    </table>

    <table class="grid">
        <thead>
            <tr>
                <th class="category">GRUPO</th>
                <th class="exam">EXAMEN</th>
                @foreach($laboratories as $lab)
                    <th>
                        {{ $date($lab->fecha) }}<br>
                        <span class="small">{{ $lab->patient?->nombre }}</span><br>
                        <span class="small">#{{ $lab->order?->codigo ?? $lab->order_id }}</span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($examsByCategory as $category => $exams)
                @foreach($exams as $index => $exam)
                    <tr>
                        @if($index === 0)
                            <td class="category" rowspan="{{ count($exams) }}">{{ $category }}</td>
                        @endif
                        <td class="exam">{{ $exam }}</td>
                        @foreach($laboratories as $lab)
                            <td class="value">{{ $resultValue($lab, $exam) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    @if($laboratories->count() === 1 && !empty($firstLab->observaciones))
        <div class="observations"><strong>OBSERVACIONES:</strong><br>{{ $firstLab->observaciones }}</div>
    @endif
@endif
</body>
</html>
