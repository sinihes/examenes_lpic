@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-indigo-600 mb-4">{{ $exam->title }}</h1>

    <div class="mt-6">
    <a href="{{ route('exam.take', ['category' => $category, 'examId' => $exam->id]) }}"
       class="">
        Reintentar el cuestionario
    </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-2">Requisitos de finalización</h2>
        <ul class="list-disc list-inside text-gray-700">
            <li><strong>Hecho:</strong> Ver</li>
            <li><strong>Método de calificación:</strong> Calificación más alta</li>
        </ul>
    </div>
    

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Resumen de sus intentos previos</h2>

        @if($results->isEmpty())
            <p class="text-gray-500">Aún no ha realizado este examen.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                    <tr>
                        <th class="px-4 py-2">Intento</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Puntos</th>
                        <th class="px-4 py-2">Calificación</th>
                        <th class="px-4 py-2">Enviado</th>
                        <th class="px-4 py-2">Revisión</th>
                    </tr>

                </thead>
                <tbody class="text-sm text-gray-800">
                    @foreach($results as $index => $result)

                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">Finalizado</td>
                        <td class="px-4 py-2">{{ $result->score }} / {{ $result->total }}</td>
                        <td class="px-4 py-2">{{ number_format(($result->score / $result->total) * 10, 2) }}</td>
                        <td class="px-4 py-2">{{ $result->created_at->translatedFormat('l, j \\d\\e F \\d\\e Y, H:i') }}</td>
                        <td class="px-4 py-2">
                            <a href="{{route('exam.revision',['category' => $category, 'revisionId' => $result->id])}}" class="text-indigo-600 hover:underline">Revisión</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-600">
            <strong>Calificación más alta:</strong>
            {{ number_format(($results->max('score') / $results->first()->total) * 10, 2) }} / 10,00
        </div>
        @endif
    </div>
</div>
@endsection

