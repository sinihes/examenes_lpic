<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{

public function statistics($category, $examId){
    $filePath = "exams/{$category}/{$examId}.json";

    if (!Storage::exists($filePath)) {
        abort(404, "Examen no encontrado");
    }

    $content = Storage::get($filePath);
    $exams = json_decode($content);


    $results = Auth::user()
        ->results()
        ->where('exam_id', $examId)
        ->where('category', $category)
        ->orderBy('created_at', 'asc')
        ->get();

    $highestNote = collect($results)->map(function($result) {
    return ($result->score / $result->total) * 10;
    })->max();

    return view('exam.statistics',[
        'exams' => $exams,
        'results' => $results,
        'examId' => $examId,
        'category' => $category,
        'highestNote' => $highestNote,
    ]);
}


public function take($category, $examId)
{
    $filePath = "exams/{$category}/{$examId}.json";

    if (!\Storage::exists($filePath)) {
        abort(404, "Examen no encontrado");
    }

    $content = \Storage::get($filePath);
    $exam = json_decode($content);

    return view('exam.take', [
        'exam' => $exam,
        'category' => $category,
        'examId' => $examId
    ]);
}

public function submit(Request $request, $category, $examId)
{
    $filePath = "exams/{$category}/{$examId}.json";

    if (!\Storage::exists($filePath)) {
        abort(404, "Examen no encontrado");
    }

    $content = \Storage::get($filePath);
    $exam = json_decode($content, true);

    $score = 0;
    $userResponses = [];

    foreach ($exam['questions'] as $question) {
        $questionKey = 'q_' . $question['id'];
        $userAnswer = $request->input($questionKey);

        // Normalizar la respuesta para preguntas no contestadas
        if ($userAnswer === null) {
            if ($question['type'] === 'multiple') {
                $userAnswer = [];
            } else {
                $userAnswer = '';
            }
        }

        $isCorrect = $this->checkAnswer($question, $userAnswer);

        if ($isCorrect) {
            $score++;
        }

        $userResponses[] = [
            'question_id' => $question['id'],
            'question_text' => $question['question'], // Cambiado de 'text' a 'question'
            'question_type' => $question['type'],
            'user_answer' => $userAnswer,
            'correct_answer' => $this->getCorrectAnswer($question),
            'is_correct' => $isCorrect,
            'feedback' => $question['feedback'] ?? '',  // <-- Agregar retroalimentación aquí
        ];
    }

    auth()->user()->results()->create([
        'exam_id' => $examId,
        'category' => $category,
        'score' => $score,
        'total' => count($exam['questions']),
        'details' => json_encode($userResponses),
    ]);

    return redirect()->route('exam.result', [
        'category' => $category,
        'examId' => $examId
    ]);
}

private function checkAnswer($question, $userAnswer)
{
    switch ($question['type']) {
        case 'single':
            // Para preguntas no contestadas
            if (empty($userAnswer)) {
                return false;
            }

            foreach ($question['options'] as $option) {
                if ($option['correct'] && $option['label'] === $userAnswer) {
                    return true;
                }
            }
            return false;

        case 'multiple':
            // Aseguramos que $userAnswer sea un array
            if (!is_array($userAnswer)) {
                return false;
            }

            // Obtenemos todas las opciones correctas
            $correctLabels = array_filter($question['options'], function($opt) {
                return $opt['correct'];
            });
            $correctLabels = array_column($correctLabels, 'label');

            // Verificamos que todas las correctas estén seleccionadas
            // y que no haya selecciones incorrectas
            $correctSelected = array_intersect($userAnswer, $correctLabels);
            $incorrectSelected = array_diff($userAnswer, $correctLabels);

            return count($correctSelected) === count($correctLabels) &&
                   empty($incorrectSelected);

        case 'text':
            // Para preguntas no contestadas
            if (empty(trim($userAnswer))) {
                return false;
            }
            return strtolower(trim($question['answer'])) === strtolower(trim($userAnswer));

        default:
            return false;
    }
}


public function result($category, $examId)
{
    $filePath = "exams/{$category}/{$examId}.json";

    if (!\Storage::exists($filePath)) {
        abort(404, "Examen no encontrado");
    }

    $content = \Storage::get($filePath);
    $exam = json_decode($content,true);

    $result = auth()->user()
        ->results()
        ->where('exam_id', $examId)
        ->where('category', $category)
        ->latest()
        ->first();

    if (!$result) {
        return redirect()->route('exam.statistics', ['category' => $category, 'examId' => $examId])
            ->with('error', 'No tienes resultados para este examen.');
    }

    $details = json_decode($result->details, true);


    // Preparar las preguntas combinando datos del examen y resultados
    // Preparar las preguntas combinando datos del examen y resultados
    $questions = [];

    foreach ($exam['questions'] as $question) {
        // Buscar los detalles de esta pregunta en los resultados
        $questionDetail = collect($details)->firstWhere('question_id', $question['id']);

        // Construir la estructura completa de la pregunta
        $questionData = [
            'id' => $question['id'],
            'question_text' => $question['question'],
            'question_type' => $question['type'],
            'is_correct' => $questionDetail['is_correct'] ?? false,
            'user_answer' => $questionDetail['user_answer'] ?? null,
            'correct_answer' => $questionDetail['correct_answer'] ?? $this->getCorrectAnswer($question),
            'feedback' => $questionDetail['feedback'] ?? null,
        ];

        // Solo agregar opciones si es pregunta de selección
        if (in_array($question['type'], ['single', 'multiple'])) {
            $questionData['options'] = $question['options'];
        }

        $questions[] = $questionData;
    }



    return view('exam.result', [
        'exam' => (object)$exam, // Convertir a objeto para mantener compatibilidad
        'result' => $result,
        'questions' => $questions, // Enviar las preguntas preparadas
        'category' => $category,
        'examId' => $examId
    ]);
}


public function revision($category, $revisionId)
{
    $user = auth()->user();
    $result = $user->results()
        ->where('id', $revisionId)
        ->where('category', $category)
        ->firstOrFail();

    // Obtener el examen original para tener las opciones completas
    $filePath = "exams/{$category}/{$result->exam_id}.json";
    $exam = json_decode(Storage::get($filePath), true);

    // Decodificar los detalles guardados
    $details = json_decode($result->details, true);

    // Preparar las preguntas combinando datos del examen y resultados
    $questions = [];

    foreach ($exam['questions'] as $question) {
        // Buscar los detalles de esta pregunta en los resultados
        $questionDetail = collect($details)->firstWhere('question_id', $question['id']);

        // Construir la estructura completa de la pregunta
        $questionData = [
            'id' => $question['id'],
            'question_text' => $question['question'],
            'question_type' => $question['type'],
            'is_correct' => $questionDetail['is_correct'] ?? false,
            'user_answer' => $questionDetail['user_answer'] ?? null,
            'correct_answer' => $questionDetail['correct_answer'] ?? $this->getCorrectAnswer($question),
            'feedback' => $questionDetail['feedback'] ?? null,
        ];

        // Solo agregar opciones si es pregunta de selección
        if (in_array($question['type'], ['single', 'multiple'])) {
            $questionData['options'] = $question['options'];
        }

        $questions[] = $questionData;
    }

    return view('exam.revision', [
        'result' => $result,
        'questions' => $questions,
    ]);
}

private function getCorrectAnswer($question)
{
    switch ($question['type']) {
        case 'single':
            foreach ($question['options'] as $option) {
                if ($option['correct']) {
                    return strtoupper($option['label']) . '. ' . $option['text'];
                }
            }
            return 'No disponible';

        case 'multiple':
            $correctOptions = array_filter($question['options'], function($opt) {
                return $opt['correct'];
            });

            $answers = array_map(function($opt) {
                return strtoupper($opt['label']) . '. ' . $opt['text'];
            }, $correctOptions);

            return implode(', ', $answers);

        case 'text':
            return $question['answer'];

        default:
            return 'No disponible';
    }
}

}
