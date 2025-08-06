@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $exam->title }}</h1>
        <div class="text-lg mb-4">
            <span class="font-semibold">Resultado:</span>
            <span class="{{ $result->score >= ($result->total / 2) ? 'text-green-600' : 'text-red-600' }}">
                {{ $result->score }} / {{ $result->total }}
            </span>
            ({{ number_format(($result->score / $result->total) * 100, 1) }}%)
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
            <div class="bg-{{ $result->score >= ($result->total / 2) ? 'green' : 'red' }}-500 h-4 rounded-full"
                 style="width: {{ ($result->score / $result->total) * 100 }}%"></div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        @foreach ($exam->questions as $question)
            <div class="mb-8 p-4 border rounded shadow-sm">
                <p class="font-semibold mb-3 text-lg">{{ $loop->iteration }}. {{ $question->question }}</p>

                @php
                    $questionKey = 'q_'.$question->id;
                    $resultData = $results[$questionKey] ?? null;
                @endphp

                @if ($question->type === 'single')
                    @foreach ($question->options as $option)
                        @php
                            $isUserAnswer = $resultData && $resultData['user_answer'] === $option->label;
                            $isCorrectOption = $option->correct;
                        @endphp
                        <div class="mb-2 flex items-center">
                            <input type="radio" name="{{ $questionKey }}" value="{{ $option->label }}"
                                   class="mr-3 h-5 w-5" @if($isUserAnswer) checked @endif disabled>
                            <span class="{{ $isUserAnswer && !$resultData['is_correct'] ? 'text-red-600' : '' }}
                                      {{ $isCorrectOption ? 'font-semibold text-green-600' : '' }}">
                                {{ strtoupper($option->label) }}. {{ $option->text }}
                            </span>
                            @if($isUserAnswer)
                                <span class="ml-3 {{ $resultData['is_correct'] ? 'bg-green-500' : 'bg-red-500' }}
                                           text-white text-xs px-2 py-1 rounded-full">
                                    {{ $resultData['is_correct'] ? '✓ Correcto' : '✗ Incorrecto' }}
                                </span>
                            @endif
                        </div>
                    @endforeach

                @elseif ($question->type === 'multiple')
                    @foreach ($question->options as $option)
                        @php
                            $userAnswers = $resultData ? (array)$resultData['user_answer'] : [];
                            $isUserAnswer = in_array($option->label, $userAnswers);
                            $isCorrectOption = $option->correct;
                            $isActuallyCorrect = $isUserAnswer && $isCorrectOption;
                            $isIncorrectSelection = $isUserAnswer && !$isCorrectOption;
                            $isMissingCorrect = !$isUserAnswer && $isCorrectOption;
                        @endphp
                        <div class="mb-2 flex items-center">
                            <input type="checkbox" name="{{ $questionKey }}[]" value="{{ $option->label }}"
                                   class="mr-3 h-5 w-5" @if($isUserAnswer) checked @endif disabled>
                            <span class="{{ $isIncorrectSelection ? 'text-red-600' : '' }}
                                      {{ $isCorrectOption ? 'font-semibold text-green-600' : '' }}">
                                {{ strtoupper($option->label) }}. {{ $option->text }}
                            </span>
                            @if($isActuallyCorrect)
                                <span class="ml-3 bg-green-500 text-white text-xs px-2 py-1 rounded-full">✓ Correcto</span>
                            @elseif($isIncorrectSelection)
                                <span class="ml-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full">✗ Incorrecto</span>
                            @endif
                        </div>
                    @endforeach

                @elseif ($question->type === 'text')
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tu respuesta:</label>
                        <input type="text" name="{{ $questionKey }}"
                               value="{{ $resultData['user_answer'] ?? '' }}"
                               class="border p-2 rounded w-full mb-2" readonly
                               class="{{ $resultData && !$resultData['is_correct'] ? 'border-red-300 bg-red-50' : 'border-gray-300' }}">
                    </div>

                    @if($resultData && !$resultData['is_correct'])
                        <div class="text-sm">
                            <span class="bg-red-500 text-white px-2 py-1 rounded-full">✗ Incorrecto</span>
                            <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded">
                                <span class="font-semibold text-green-700">Respuesta correcta:</span>
                                <span class="text-green-700">{{ $resultData['correct_answer'] }}</span>
                            </div>
                        </div>
                    @elseif($resultData && $resultData['is_correct'])
                        <span class="bg-green-500 text-white px-2 py-1 rounded-full">✓ Correcto</span>
                    @endif
                @endif
            </div>
        @endforeach

        <div class="flex justify-between mt-8">
            <a href="{{ route('exam.statistics', ['category' => $category, 'examId' => $examId]) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition duration-200">
                Ver estadísticas
            </a>
            <a href="{{ route('exam.take', ['category' => $category, 'examId' => $examId]) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                Intentar de nuevo
            </a>
        </div>
    </div>
</div>
@endsection
