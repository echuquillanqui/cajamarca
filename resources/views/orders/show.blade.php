@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('orders.index') }}" class="text-decoration-none text-muted small fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver a Órdenes
        </a>
        <div>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-1">
                <i class="fa-solid fa-pen me-1"></i> Editar General
            </a>
            @if($order->tipo === 'HEMODIALISIS')
                <a href="{{ route('orders.hemodialysis.pdf', $order->id) }}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3 me-1">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF Hemodiálisis
                </a>
            @endif
            <button onclick="window.print();" class="btn btn-light btn-sm rounded-pill px-3 border">
                <i class="fa-solid fa-print me-1"></i> Imprimir Cabecera
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-file-medical text-primary me-2"></i>Estructura de la Orden</span>
            <span class="badge bg-secondary rounded-pill">ID #{{ $order->id }}</span>
        </div>
        <div class="card-body p-4">
            <div class="row row-gap-3">
                <div class="col-md-6">
                    <small class="text-muted d-block text-uppercase fw-semibold">Paciente</small>
                    <h5 class="text-dark fw-bold mb-0">{{ $order->patient->nombre }}</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted d-block text-uppercase fw-semibold mb-1">Tipo de Servicio</small>
                    <span class="badge bg-dark px-3 py-2 text-white fs-6 mb-2">{{ $order->tipo }}</span>
                </div>
            </div>

            <hr class="my-4 opacity-25">

            <div class="row g-3">
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block">Código Único</small>
                    <span class="fw-semibold text-dark">{{ $order->codigo }}</span>
                </div>
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block">Fecha Orden</small>
                    <span class="fw-semibold text-dark">{{ $order->fecha ? $order->fecha->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block">Estado Clínico</small>
                    <span class="fw-bold text-primary">{{ $order->estado }}</span>
                </div>
                <div class="col-6 col-sm-3">
                    <small class="text-muted d-block">Médico Responsable</small>
                    <span class="fw-semibold text-dark">{{ $order->user->name }}</span>
                </div>
            </div>

            <hr class="my-4 opacity-25">

            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-notes-medical text-primary me-2"></i>Observaciones de la Orden</h6>
                <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line; min-height: 100px;">
                    {{ $order->observaciones ?? 'Sin anotaciones registradas.' }}
                </div>
            </div>

            <div class="alert alert-info d-flex justify-content-between align-items-center mb-0 mt-2">
                <div>
                    <i class="fa-solid fa-circle-info me-2 fs-5"></i>
                    <span>Esta orden genera un flujo en el módulo de <strong>{{ $order->tipo }}</strong>.</span>
                </div>
                @if($order->tipo === 'HEMODIALISIS')
                    <a href="{{ route('orders.hemodialysis.pdf', $order->id) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill shadow-sm">
                        Descargar ficha PDF <i class="fa-solid fa-file-pdf ms-1"></i>
                    </a>
                @else
                    <button class="btn btn-sm btn-primary rounded-pill shadow-sm" disabled>
                        Proceder a {{ strtolower($order->tipo) }} <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection