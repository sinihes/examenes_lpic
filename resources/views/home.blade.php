@extends('layouts.app')

@section('content')
<div class="container-fluid bg-light py-5" style="background: linear-gradient(to right, #f8f9fa, #e9ecef); min-height: 100vh;">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary fw-bold mb-3">
                ¡Bienvenido, {{ auth()->user()->name }}!
            </h1>
            <p class="lead text-muted">
                Selecciona tu certificación LPIC
            </p>
        </div>

        <!-- Cards Container -->
        <div class="row justify-content-center g-4">
            <!-- LPIC-1 Card -->
            <div class="col-md-6">
                <a href="{{ route('lpic1_0') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 hover-shadow transition-all">
                        <div class="card-body text-center p-5">
                            <div class="bg-warning bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4">
                                <i class="fas fa-shield-alt fa-4x text-warning"></i>
                            </div>
                            <h2 class="card-title fw-bold text-dark mb-3">LPIC-1</h2>
                            <h2 class="card-title fw-bold text-dark mb-3">Exámen-1</h2>
                            <p class="card-text text-muted mb-4">
                            <p class="card-text text-muted mb-4">
                                Fundamentos de Linux y administración básica del sistema
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <button class="btn btn-outline-primary w-100 fw-bold">Comenzar</button>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('lpic1_1') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 hover-shadow transition-all">
                        <div class="card-body text-center p-5">
                            <div class="bg-warning bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4">
                                <i class="fas fa-shield-alt fa-4x text-warning"></i>
                            </div>
                            <h2 class="card-title fw-bold text-dark mb-3">LPIC-1</h2>
                            <h2 class="card-title fw-bold text-dark mb-3">Exámen-2</h2>
                            <p class="card-text text-muted mb-4">
                                Fundamentos de Linux y administración básica del sistema
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <button class="btn btn-outline-primary w-100 fw-bold">Comenzar</button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- LPIC-2 SP Card -->
            <div class="col-md-6">
                <a href="{{ route('lpic2_1_sp') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 hover-shadow transition-all">
                        <div class="card-body text-center p-5">
                            <div class="bg-purple bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4">
                                <i class="fas fa-network-wired fa-4x text-purple"></i>
                            </div>
                            <h2 class="card-title fw-bold text-dark mb-3">LPIC-2</h2>
                            <h2 class="card-title fw-bold text-dark mb-3">Exámen-1 (Español)</h2>
                            <p class="card-text text-muted mb-4">
                                Administración avanzada de sistemas Linux y redes
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <button class="btn btn-outline-primary w-100 fw-bold">Comenzar</button>
                        </div>
                    </div>
                </a>
            </div>
                    <!-- LPIC-2-1 SP Card -->
            <div class="col-md-6">
                <a href="{{ route('lpic2_2_sp') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 hover-shadow transition-all">
                        <div class="card-body text-center p-5">
                            <div class="bg-purple bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4">
                                <i class="fas fa-network-wired fa-4x text-purple"></i>
                            </div>
                            <h2 class="card-title fw-bold text-dark mb-3">LPIC-2</h2>
                            <h2 class="card-title fw-bold text-dark mb-3">Exámen-2 (Español)</h2>
                            <p class="card-text text-muted mb-4">
                                Administración avanzada de sistemas Linux y redes
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <button class="btn btn-outline-primary w-100 fw-bold">Comenzar</button>
                        </div>
                    </div>
                </a>
            </div>
                    <!-- LPIC-2 GR Card -->
            <div class="col-md-6">
                <a href="{{ route('lpic2_1_gr') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 hover-shadow transition-all">
                        <div class="card-body text-center p-5">
                            <div class="bg-blue bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4">
                                <i class="fas fa-network-wired fa-4x text-purple"></i>
                            </div>
                            <h2 class="card-title fw-bold text-dark mb-3">LPIC-2</h2>
                            <h2 class="card-title fw-bold text-dark mb-3">Exámen-1 (Ingles)</h2>
                            <p class="card-text text-muted mb-4">
                                Administración avanzada de sistemas Linux y redes
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <button class="btn btn-outline-primary w-100 fw-bold">Comenzar</button>
                        </div>
                    </div>
                </a>
            </div>
                    <!-- LPIC-2 Card -->
            <div class="col-md-6">
                <a href="{{ route('lpic2_2_gr') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 hover-shadow transition-all">
                        <div class="card-body text-center p-5">
                            <div class="bg-blue bg-opacity-10 d-inline-flex p-4 rounded-circle mb-4">
                                <i class="fas fa-network-wired fa-4x text-purple"></i>
                            </div>
                            <h2 class="card-title fw-bold text-dark mb-3">LPIC-2</h2>
                            <h2 class="card-title fw-bold text-dark mb-3">Exámen-2 (Ingles)</h2>
                            <p class="card-text text-muted mb-4">
                                Administración avanzada de sistemas Linux y redes
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 py-3">
                            <button class="btn btn-outline-primary w-100 fw-bold">Comenzar</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Floating Penguin (Tux) -->
        <div class="position-fixed bottom-0 end-0 mb-4 me-4" style="animation: float 6s ease-in-out infinite;">
            <img src="{{ asset('images/linux-penguin.png') }}" alt="Tux" class="img-fluid" style="width: 80px;">
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    .bg-purple {
        background-color: #6f42c1;
    }

    .text-purple {
        color: #6f42c1;
    }

    .btn-outline-primary {
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection
