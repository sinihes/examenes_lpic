@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-indigo-600 mb-6">Exámenes {{ $course }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($exams as $exam)
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-indigo-500">
            <h2 class="text-xl font-semibold mb-2">{{ $exam['title'] }}</h2>
            <p class="text-gray-600 mb-4">
                {{ count($exam['questions']) }} preguntas
            </p>
            <a href="{{ route('exam.show', ['category' => $exam['category'], 'examId' => $exam['id']]) }}"
               class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Comenzar examen
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
