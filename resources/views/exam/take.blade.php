@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary mb-0">
            <i class="fas fa-clipboard-list me-2"></i>{{ $exam->title }}
        </h1>
        <div class="badge bg-primary text-white p-2">
            <span id="question-counter">1/{{ count($exam->questions) }}</span>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress mb-4" style="height: 8px;">
        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
             role="progressbar" style="width: 0%"
             id="exam-progress" aria-valuenow="0"
             aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- Exam Form -->
    <form method="POST" action="{{ route('exam.submit', ['category' => $category, 'examId' => $examId]) }}" id="exam-form">
        @csrf

        @foreach ($exam->questions as $question)
        <div class="card mb-3 border-primary border-opacity-25 question-card" id="q{{ $question->id }}-card" data-question-type="{{ $question->type }}">
            <div class="card-header bg-primary bg-opacity-10 py-3">
                <h5 class="mb-0 fw-bold">
                    <span class="text-primary">Pregunta {{ $loop->iteration }}.</span>
                    {{ $question->question }}
                </h5>
            </div>
            <div class="card-body">
                @if ($question->type === 'single')
                    <div class="list-group list-group-flush">
                        @foreach ($question->options as $option)
                        <label class="list-group-item list-group-item-action">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="q_{{ $question->id }}"
                                       value="{{ $option->label }}" required>
                                <span class="fw-bold text-primary me-2">{{ strtoupper($option->label) }}.</span>
                                {{ $option->text }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <div class="invalid-feedback d-block mt-2" id="error-q{{ $question->id }}">
                        Por favor selecciona una opción
                    </div>

                @elseif ($question->type === 'multiple')
                    <div class="list-group list-group-flush">
                        @foreach ($question->options as $option)
                        <label class="list-group-item list-group-item-action">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="q_{{ $question->id }}[]"
                                       value="{{ $option->label }}">
                                <span class="fw-bold text-primary me-2">{{ strtoupper($option->label) }}.</span>
                                {{ $option->text }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <div class="invalid-feedback d-block mt-2" id="error-q{{ $question->id }}">
                        Por favor selecciona al menos una opción
                    </div>

                @elseif ($question->type === 'text')
                    <div class="form-floating">
                        <input type="text" class="form-control" id="q_{{ $question->id }}"
                               name="q_{{ $question->id }}" placeholder="Escribe tu respuesta" required>
                        <label for="q_{{ $question->id }}">Escribe tu respuesta</label>
                    </div>
                    <div class="invalid-feedback d-block mt-2" id="error-q{{ $question->id }}">
                        Por favor escribe tu respuesta
                    </div>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-primary" id="prev-btn" disabled>
                <i class="fas fa-arrow-left me-1"></i> Anterior
            </button>
            <button type="button" class="btn btn-primary" id="next-btn">
                Siguiente <i class="fas fa-arrow-right ms-1"></i>
            </button>
            <button type="submit" class="btn btn-success d-none" id="submit-btn">
                <i class="fas fa-paper-plane me-1"></i> Enviar examen
            </button>
        </div>
    </form>
</div>

<!-- Script para navegación con validación -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questions = document.querySelectorAll('.question-card');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        const progressBar = document.getElementById('exam-progress');
        const questionCounter = document.getElementById('question-counter');
        let currentQuestion = 0;

        // Ocultar todos los mensajes de error inicialmente
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.style.display = 'none';
        });

        // Mostrar solo la primera pregunta inicialmente
        questions.forEach((q, index) => {
            q.style.display = index === 0 ? 'block' : 'none';
        });

        // Actualizar progreso y contador
        function updateProgress() {
            const progress = ((currentQuestion + 1) / questions.length) * 100;
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('aria-valuenow', progress);
            questionCounter.textContent = `${currentQuestion + 1}/${questions.length}`;
        }

        // Validar pregunta actual
        function validateCurrentQuestion() {
            const currentCard = questions[currentQuestion];
            const questionType = currentCard.dataset.questionType;
            let isValid = true;
            const errorElement = document.getElementById(`error-q${currentCard.id.split('-')[0].substring(1)}`);

            if (questionType === 'single') {
                const selected = currentCard.querySelector('input[type="radio"]:checked');
                isValid = !!selected;
            }
            else if (questionType === 'multiple') {
                const selected = currentCard.querySelectorAll('input[type="checkbox"]:checked');
                isValid = selected.length > 0;
            }
            else if (questionType === 'text') {
                const input = currentCard.querySelector('input[type="text"]');
                isValid = input.value.trim() !== '';
            }

            if (!isValid) {
                errorElement.style.display = 'block';
                currentCard.classList.add('border-danger');
                return false;
            }

            errorElement.style.display = 'none';
            currentCard.classList.remove('border-danger');
            return true;
        }

        // Navegación siguiente
        nextBtn.addEventListener('click', function() {
            if (!validateCurrentQuestion()) return;

            questions[currentQuestion].style.display = 'none';
            currentQuestion++;
            questions[currentQuestion].style.display = 'block';

            prevBtn.disabled = currentQuestion === 0;

            if (currentQuestion === questions.length - 1) {
                nextBtn.classList.add('d-none');
                submitBtn.classList.remove('d-none');
            }

            updateProgress();
        });

        // Navegación anterior
        prevBtn.addEventListener('click', function() {
            questions[currentQuestion].style.display = 'none';
            currentQuestion--;
            questions[currentQuestion].style.display = 'block';

            prevBtn.disabled = currentQuestion === 0;
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');

            updateProgress();
        });

        // Validación antes de enviar
        /**document.getElementById('exam-form').addEventListener('submit', function(e) {
            if (!validateCurrentQuestion()) {
                e.preventDefault();
                return;
            }

            if(!confirm('¿Estás seguro de que quieres enviar el examen? No podrás modificarlo después.')) {
                e.preventDefault();
            }
        });**/

        // Validar al cambiar respuestas
        document.querySelectorAll('input[type="radio"], input[type="checkbox"], input[type="text"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.type === 'text') {
                    validateCurrentQuestion();
                }
            });
        });

        updateProgress();
    });
</script>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .question-card {
        transition: all 0.3s ease;
    }
    .list-group-item {
        border-left: 0;
        border-right: 0;
        padding: 1rem 1.25rem;
    }
    .list-group-item:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .form-check-input {
        margin-top: 0.25rem;
    }
    .form-floating label {
        color: #6c757d;
    }
    .progress {
        border-radius: 10px;
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
    }
    .border-danger {
        border-color: #dc3545 !important;
    }
</style>
@endsection
