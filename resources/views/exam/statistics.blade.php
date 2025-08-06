@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-primary mb-2">{{ $exams->title }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ $category }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resultados</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('exam.take', ['category' => $category, 'examId' => $examId]) }}"
           class="btn btn-primary">
           <i class="fas fa-redo me-2"></i>Reintentar examen
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-chart-line text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Mejor calificación</h6>
                            <p class="h3 fw-bold mb-0">{{ number_format($highestNote, 2) }}/10.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-list-ol text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Total intentos</h6>
                            <p class="h3 fw-bold mb-0">{{ count($results) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-question-circle text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Preguntas</h6>
                            <p class="h3 fw-bold mb-0">{{ $results->first()->total ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attempts Table -->
    <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historial de intentos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">Intento</th>
                            <th scope="col">Realizado</th>
                            <th scope="col">Preguntas</th>
                            <th scope="col">Calificación/10.00</th>
                            <th scope="col" class="text-end pe-4">Revisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)

                        <tr>
                            <th scope="row" class="ps-4">{{ $result->id }}</th>
                            <td>{{ $result->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $result->total }}</td>
                            <td>
                                <span class="badge bg-{{ ($result->score/$result->total)*10 >= 5 ? 'success' : 'danger' }}">
                                    {{ number_format(($result->score/$result->total)*10, 2) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('exam.revision', ['category' => $category, 'revisionId' => $result->id]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                   <i class="fas fa-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .card {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }
</style>
@endsection
