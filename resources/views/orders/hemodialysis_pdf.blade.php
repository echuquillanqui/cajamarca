<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Hemodiálisis</title>
    <style>
        @page { margin: 18px 28px; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #222; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 7px; margin-bottom: 18px; position: relative; }
        .brand-left, .brand-right { position: absolute; top: 0; width: 90px; font-size: 8px; font-weight: bold; color: #555; }
        .brand-left { left: 0; text-align: left; } .brand-right { right: 0; text-align: right; }
        .hospital { font-size: 10px; font-weight: bold; letter-spacing: .2px; }
        .unit { font-size: 16px; font-style: italic; font-weight: bold; }
        .phone { font-size: 8px; }
        .title-bar { background: #3b3b3b; color: white; font-weight: bold; font-size: 13px; padding: 4px 7px; margin-bottom: 14px; }
        .title-date { float: right; min-width: 195px; text-align: center; }
        .line { border-bottom: 1px solid #222; display: inline-block; min-height: 11px; padding: 0 3px; vertical-align: bottom; color: #0f4c81; font-weight: bold; }
        .w40{width:40px}.w55{width:55px}.w65{width:65px}.w80{width:80px}.w100{width:100px}.w130{width:130px}.w180{width:180px}.w250{width:250px}.w360{width:360px}
        .row { margin-bottom: 7px; }
        .section { border: 1px solid #555; border-radius: 15px; padding: 10px 16px; margin-bottom: 9px; }
        .section-title { font-weight: bold; font-style: italic; margin: -3px 0 8px 0; }
        .grid { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .grid td, .grid th { border: 1px solid #555; padding: 3px; text-align: center; height: 18px; }
        .grid th { background: #e6e6e6; font-size: 8px; }
        .obs { text-align: left !important; }
        .check { border: 1px solid #555; border-radius: 50%; display: inline-block; width: 13px; height: 13px; line-height: 13px; text-align: center; font-size: 8px; }
        .filled { color: #0f4c81; font-weight: bold; }
        .signature { border-top: 1px solid #555; display: inline-block; width: 150px; text-align: center; padding-top: 4px; margin-top: 18px; }
        .page-break { page-break-after: always; }
        .soapie td { height: 80px; vertical-align: top; }
        .soapie .letter { width: 22px; text-align: center; font-style: italic; font-weight: bold; }
        .footer-box { border: 1px solid #555; padding: 6px; margin-top: 6px; }
    </style>
</head>
<body>
@php
    $patient = $order->patient;
    $fechaSesion = $medical?->fecha_sesion ?? $order->fecha;
    $age = $patient?->fecha_nacimiento ? \Carbon\Carbon::parse($patient->fecha_nacimiento)->age : null;
    $bool = fn ($value) => $value ? 'X' : '';
    $date = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '';
    $time = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('H:i') : '';
@endphp

<div class="header">
    <div class="brand-left">CAJAMARCA<br>MINSA</div>
    <div class="brand-right">NEFROLOGÍA<br>HRDC</div>
    <div class="hospital">HOSPITAL REGIONAL DOCENTE DE CAJAMARCA</div>
    <div class="unit">Unidad de Nefrología</div>
    <div class="phone">Telf.: (076)-590929 - Anexo: 273</div>
</div>

<div class="title-bar">FICHA DE HEMODIÁLISIS <span class="title-date">FECHA: {{ $date($fechaSesion) }} &nbsp; Sesión N° <span class="line w55">{{ $medical?->numero_sesion }}</span></span></div>

<div class="row">Nombre: <span class="line w360">{{ $patient?->nombre }}</span> Edad: <span class="line w40">{{ $age }}</span> Sexo: <span class="check">{{ strtoupper($patient?->sexo ?? '') === 'M' ? 'M' : (strtoupper($patient?->sexo ?? '') === 'F' ? 'F' : '') }}</span> HCL. N° <span class="line w80">{{ $patient?->dni }}</span></div>
<div class="row">DNI: <span class="line w100">{{ $patient?->dni }}</span> SIS: <span class="line w130">{{ $patient?->codigo_seguro }}</span> Servicio: <span class="line w100">{{ $medical?->servicio_procedencia }}</span> Cama: <span class="line w55">{{ $medical?->cama }}</span> Telef: <span class="line w80">{{ $patient?->telefono }}</span></div>

<div class="section">
    <div class="section-title">EVALUACIÓN CLÍNICA <span style="float:right; font-style:normal;">PA= <span class="line w55">{{ $medical?->pa }}</span> FC= <span class="line w40">{{ $medical?->fc }}</span> FR= <span class="line w40">{{ $medical?->fr }}</span> SAT= <span class="line w40">{{ $medical?->sat }}</span></span></div>
    <div style="min-height:42px; border-bottom:1px solid #ddd; white-space: pre-line;" class="filled">{{ $medical?->evaluacion }}</div>
    <div style="margin-top:8px;">Peso seco: <span class="line w100">{{ $medical?->peso_seco }}</span> Diuresis: <span class="line w130">{{ $medical?->diuresis }}</span> Alergia a medicamentos: (SI) <span class="check">{{ $bool($medical?->alergias) }}</span> (NO) <span class="check">{{ $medical && !$medical->alergias ? 'X' : '' }}</span> <span class="filled">{{ $medical?->alergias_descripcion }}</span></div>
</div>

<div class="section">
    <div class="section-title">PRESCRIPCIÓN</div>
    <table style="width:100%;"><tr>
        <td>Técnica: <span class="line w80">{{ $medical?->tecnica }}</span><br>Frecuencia: <span class="line w80">{{ $medical?->frecuencia }}</span><br>Acceso: <span class="line w80">{{ $medical?->acceso }}</span><br>Heparina: <span class="line w80">{{ $medical?->heparina }}</span></td>
        <td>Filtro (m²): <span class="line w80">{{ $medical?->filtro }}</span><br>Membrana: <span class="line w80">{{ $medical?->membrana }}</span><br>QB: <span class="line w80">{{ $medical?->qb }}</span><br>QD: <span class="line w80">{{ $medical?->qd }}</span></td>
        <td>Tiempo: <span class="line w80">{{ $medical?->tiempo_horas }}</span><br>Sodio: <span class="line w80">{{ $medical?->sodio_mEq }}</span><br>Perfil Sodio: <span class="line w80">{{ $medical?->perfil_sodio }}</span><br>T° de L.D.: <span class="line w80">{{ $medical?->tdld }}</span></td>
        <td>UFT (ml): <span class="line w80">{{ $medical?->uft }}</span><br>UF aislada: <span class="line w80">{{ $medical?->uf_asilada }}</span><br>Perfil UF: <span class="line w80">{{ $medical?->perfil_uf }}</span><br>UF Efectivo: <span class="line w80">{{ $medical?->uf_efectivo }}</span></td>
    </tr></table>
    <div>Otras indicaciones:<br><div class="filled" style="min-height:30px; white-space:pre-line; border-bottom:1px solid #ddd;">{{ $medical?->otras_indicaciones }}</div><div style="text-align:right"><span class="signature">NEFRÓLOGO</span></div></div>
</div>

<div class="row">Grado de Dependencia: I <span class="check">{{ $medical?->grado_dep === 'I' ? 'X' : '' }}</span> II <span class="check">{{ $medical?->grado_dep === 'II' ? 'X' : '' }}</span> III <span class="check">{{ $medical?->grado_dep === 'III' ? 'X' : '' }}</span> IV <span class="check">{{ $medical?->grado_dep === 'IV' ? 'X' : '' }}</span></div>
<div class="row">Grupo y factor(RH): <span class="line w100">{{ $medical?->grup_fact }}</span> Transfusiones: (SÍ) <span class="check">{{ $bool($medical?->transfuciones) }}</span> (NO) <span class="check">{{ $medical && !$medical->transfuciones ? 'X' : '' }}</span> T°Inicial: <span class="line w65">{{ $medical?->t_inicial }}</span> T°Final: <span class="line w65">{{ $medical?->t_final }}</span> Peso inicial: <span class="line w65">{{ $medical?->p_inicial }}</span> Peso final: <span class="line w65">{{ $medical?->p_final }}</span></div>

<table class="grid"><thead><tr><th>HORA</th><th>PA</th><th>PAM</th><th>FC</th><th>SaO</th><th>UF</th><th>SODIO</th><th>QB</th><th>RA</th><th>RV</th><th>PTM</th><th>OBSERVACIONES</th><th>LABORATORIO</th></tr></thead><tbody>
@foreach($treatments->pad(12, null)->take(12) as $treatment)
<tr><td>{{ $treatment ? $time($treatment->hora) : '' }}</td><td>{{ $treatment?->pa }}</td><td>{{ $treatment?->pam }}</td><td>{{ $treatment?->fc }}</td><td>{{ $treatment?->sao2 }}</td><td>{{ $treatment?->uf_hora }}</td><td>{{ $treatment?->sodio }}</td><td>{{ $treatment?->qb }}</td><td>{{ $treatment?->ra }}</td><td>{{ $treatment?->rv }}</td><td>{{ $treatment?->ptm }}</td><td class="obs">{{ $treatment?->observaciones }}</td><td>{{ $treatment?->laboratorio_control }}</td></tr>
@endforeach
</tbody></table>

<div class="footer-box">UF. Efectivo: <span class="line w130">{{ $nurse?->uf_efectivo }}</span> Aspecto de Filtro: <span class="line w150">{{ $nurse?->asp_filtro }}</span> EPO: <span class="line w80">{{ $nurse?->epo }}</span> HIERRO: <span class="line w80">{{ $nurse?->hierro }}</span> VIT. B12: <span class="line w80">{{ $nurse?->vitb12 }}</span><span style="float:right;"><span class="signature">ENFERMERA: Inicia</span> &nbsp; <span class="signature">ENFERMERA: Finaliza</span></span></div>

<div class="page-break"></div>

<div class="header"><div class="brand-left">CAJAMARCA<br>MINSA</div><div class="brand-right">NEFROLOGÍA<br>HRDC</div><div class="hospital">HOSPITAL REGIONAL DOCENTE DE CAJAMARCA</div><div class="unit">Unidad de Nefrología</div><div class="phone">Telf.: (076)-590929 - Anexo: 273</div></div>
<div class="title-bar">NOTAS DE ENFERMERÍA DE HEMODIÁLISIS <span class="title-date">{{ $date($fechaSesion) }}</span></div>
<table class="grid soapie"><thead><tr><th style="width:55px;">HORA</th><th style="width:24px;"></th><th>NOTA DE ENFERMERÍA</th><th style="width:105px;">FIRMA</th></tr></thead><tbody>
@foreach([['hora1','S','s_subjetivo'],['hora2','O','o_objetivo'],['hora3','A','a_analisis'],['hora4','P','p_planificacion'],['hora5','I','i_intervencion'],['hora6','E','e_evaluacion']] as [$hour,$letter,$field])
<tr><td>{{ $time(data_get($nurse, $hour)) }}</td><td class="letter">{{ $letter }}</td><td class="obs filled" style="white-space:pre-line;">{{ data_get($nurse, $field) }}</td><td></td></tr>
@endforeach
</tbody></table>
<div class="footer-box">Nombre del paciente: <span class="line w360">{{ $patient?->nombre }}</span> Servicio: <span class="line w100">{{ $medical?->servicio_procedencia }}</span> N° Cama <span class="line w65">{{ $medical?->cama }}</span><br>Fecha de ingreso a terapia de H.D.: <span class="line w180">{{ $date($fechaSesion) }}</span> HCL: <span class="line w100">{{ $patient?->dni }}</span> N° Seguro: <span class="line w130">{{ $patient?->codigo_seguro }}</span></div>
</body>
</html>
