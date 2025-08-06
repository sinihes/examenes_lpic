<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LpicController extends Controller
{
    public function lpic1_0()
    {
        $exams = $this->loadExams('lpic1_0');
        return view('pages.lpic1_0', [
            'course' => 'LPIC-1',
            'exams' => $exams
        ]);
    }

    public function lpic1_1()
    {
        $exams = $this->loadExams('lpic1_1');
        return view('pages.lpic1_1', [
            'course' => 'LPIC-1',
            'exams' => $exams
        ]);
    }

    public function lpic2_1_gr()
    {
        $exams = $this->loadExams('lpic2_1_gr');
        return view('pages.lpic2_1_gr', [
            'course' => 'LPIC-2',
            'exams' => $exams
        ]);
    }

        public function lpic2_1_sp()
    {
        $exams = $this->loadExams('lpic2_1_sp');
        return view('pages.lpic2_1_sp', [
            'course' => 'LPIC-2',
            'exams' => $exams
        ]);
    }

        public function lpic2_2_gr()
    {
        $exams = $this->loadExams('lpic2_2_gr');
        return view('pages.lpic2_2_gr', [
            'course' => 'LPIC-2',
            'exams' => $exams
        ]);
    }

        public function lpic2_2_sp()
    {
        $exams = $this->loadExams('lpic2_2_sp');
        return view('pages.lpic2_2_sp', [
            'course' => 'LPIC-2',
            'exams' => $exams
        ]);
    }


private function loadExams(string $category)
{

    $basePath = "exams/{$category}";
    $absolutePath = storage_path("app/{$basePath}");

    // Verificación detallada
    if (!Storage::exists($basePath)) {
        \Log::error("No se encontró el directorio: {$absolutePath}");
        return [];
    }

    $files = Storage::files($basePath);
    \Log::info("Archivos encontrados en {$category}: " . json_encode($files));

    $exams = [];




    foreach ($files as $file) {
        try {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'json') {
                continue;
            }

            $content = Storage::disk('local')->get($file);
            $examData = json_decode($content);

            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error("JSON inválido en {$file}: " . json_last_error_msg());
                continue;
            }
            $examData = json_decode($content);
            $examData->category = $category;
            $examData->totalIntentos = \App\Models\Result::where('category', $category)->count();
            $examData->file_path = $file; // Para debug
            $exams[] = $examData;
        } catch (\Exception $e) {
            \Log::error("Error procesando {$file}: " . $e->getMessage());
        }

    }

    // Ordenar los exámenes alfabéticamente por una propiedad (ej. 'name')
    usort($exams, fn($a, $b) => intval($a->id) <=> intval($b->id));


    return $exams;
}

}
