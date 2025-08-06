@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold text-primary mb-0">
            <i class="fas fa-clipboard-list me-2"></i>Exámenes {{ $course }}
        </h2>
        <span class="badge bg-primary">
            {{ count($exams) }} exámenes disponibles
        </span>
    </div>
    <!-- Lista de exámenes -->
    <div class="list-group">
        @foreach($exams as $exam)

        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                    <i class="fas fa-file-alt text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $exam->title }}</h6>
                    <small class="text-muted">{{ count($exam->questions) }} preguntas</small>
                </div>
            </div>
            <a href="/exam/{{ $exam->category }}/{{ $exam->id }}"
               class="btn btn-sm btn-outline-primary">
               <i class="fas fa-play me-1"></i>Iniciar
            </a>
        </div>
        @endforeach
    </div>

    <!-- Estadísticas -->
    <div class="mt-4 pt-3 border-top">
        <div class="d-flex align-items-center">
            <i class="fas fa-chart-line text-muted me-2"></i>
            <small class="text-muted">
                Tienes {{ $exam->totalIntentos }} intentos registrados
            </small>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0d6efd;
    }
    .btn-outline-primary {
        transition: all 0.2s;
    }
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
</style>

<!-- Iconos de Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
