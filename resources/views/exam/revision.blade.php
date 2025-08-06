@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Revisión del examen</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Categoría:</strong> {{ $result->category }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Puntuación:</strong>
                        <span class="badge bg-{{ $result->score >= ($result->total/2) ? 'success' : 'danger' }}">
                            {{ $result->score }} / {{ $result->total }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>Fecha:</strong> {{ $result->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    @foreach ($questions as $q)
        <div class="card mb-4 border-{{ $q['is_correct'] ? 'success' : 'danger' }}">
            <div class="card-header bg-light">
                <h5 class="mb-0">❓ Pregunta {{ $loop->iteration }}</h5>
            </div>
            <div class="card-body">
                <p class="lead">{{ $q['question_text'] }}</p>

                @if (isset($q['options']) && in_array($q['question_type'], ['single', 'multiple']))
                    <div class="options-container mb-3">
                        @foreach ($q['options'] as $opt)
                            @php
                                $isUserAnswer = false;
                                if ($q['question_type'] === 'single') {
                                    $isUserAnswer = isset($q['user_answer']) && $opt['label'] === $q['user_answer'];
                                } elseif ($q['question_type'] === 'multiple') {
                                    $isUserAnswer = isset($q['user_answer']) && in_array($opt['label'], (array) $q['user_answer']);
                                }

                                $isCorrectOption = $opt['correct'] ?? false;

                                $optionClass = '';
                                if ($isUserAnswer && $isCorrectOption) {
                                    $optionClass = 'bg-success bg-opacity-10';
                                } elseif ($isUserAnswer && !$isCorrectOption) {
                                    $optionClass = 'bg-danger bg-opacity-10';
                                } elseif (!$isUserAnswer && $isCorrectOption) {
                                    $optionClass = 'border-success';
                                }
                            @endphp
                            <div class="p-2 mb-2 border rounded {{ $optionClass }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="{{ $q['question_type'] === 'single' ? 'radio' : 'checkbox' }}"
                                           id="opt-{{ $loop->parent->iteration }}-{{ $loop->iteration }}"
                                           @if($isUserAnswer) checked @endif disabled>
                                    <label class="form-check-label" for="opt-{{ $loop->parent->iteration }}-{{ $loop->iteration }}">
                                        <strong>{{ strtolower($opt['label']) }}.</strong> {{ $opt['text'] }}
                                        @if($isUserAnswer && $isCorrectOption)
                                            <span class="text-success ms-2">✔ Correcto</span>
                                        @elseif($isUserAnswer && !$isCorrectOption)
                                            <span class="text-danger ms-2">✘ Incorrecto</span>
                                        @elseif(!$isUserAnswer && $isCorrectOption)
                                            <span class="text-success ms-2">(Respuesta correcta)</span>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="alert alert-success">
                        <strong>Respuesta correcta:</strong>
                        @php
                            $correctOptions = collect($q['options'])->filter(function($o) {
                                return isset($o['correct']) && $o['correct'];
                            });
                            $correctAnswers = $correctOptions->map(function($o) {
                                return strtolower($o['label']) . '. ' . $o['text'];
                            })->implode(', ');
                        @endphp
                        {{ $correctAnswers }}
                    </div>

                @elseif ($q['question_type'] === 'text')
                    <div class="mb-3">
                        <p class="mb-1"><strong>Tu respuesta:</strong></p>
                        <div class="p-2 border rounded {{ $q['is_correct'] ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
                            {{ $q['user_answer'] ?? '(Sin respuesta)' }}
                        </div>
                    </div>
                    <div class="alert alert-success">
                        <strong>Respuesta correcta:</strong> {{ $q['correct_answer'] ?? 'No disponible' }}
                    </div>
                @endif

                @if (!empty($q['feedback']))
                    <div class="alert alert-info mt-3">
                        <strong>📌 Retroalimentación:</strong> {{ $q['feedback'] }}
                    </div>
                @endif

                <div class="mt-3">
                    <span class="badge bg-{{ $q['is_correct'] ? 'success' : 'danger' }}">
                        {{ $q['is_correct'] ? '✅ Correcta' : '❌ Incorrecta' }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('exam.statistics', ['category' => $result->category, 'examId' => $result->exam_id]) }}"
           class="btn btn-outline-secondary">
            ← Volver a estadísticas
        </a>
        <a href="{{ route('exam.take', ['category' => $result->category, 'examId' => $result->exam_id]) }}"
           class="btn btn-primary">
            Intentar de nuevo
        </a>
    </div>
</div>
@endsection
