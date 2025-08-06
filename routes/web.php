<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LpicController;
use App\Http\Controllers\ExamController;


Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/exam/{id}', [ExamController::class, 'show']);
    Route::post('/exam/{id}/submit', [ExamController::class, 'submit']);


    // Listados
    Route::get('/lpic1_0', [LpicController::class, 'lpic1_0'])->name('lpic1_0');
    Route::get('/lpic1_1', [LpicController::class, 'lpic1_1'])->name('lpic1_1');
    Route::get('/lpic2_1_sp', [LpicController::class, 'lpic2_1_sp'])->name('lpic2_1_sp');
    Route::get('/lpic2_1_gr', [LpicController::class, 'lpic2_1_gr'])->name('lpic2_1_gr');
    Route::get('/lpic2_2_sp', [LpicController::class, 'lpic2_2_sp'])->name('lpic2_2_sp');
    Route::get('/lpic2_2_gr', [LpicController::class, 'lpic2_2_gr'])->name('lpic2_2_gr');

    // Mostrar estadísticas / historial del examen (antes era 'show')
    Route::get('/exam/{category}/{examId}', [ExamController::class, 'statistics'])
     ->name('exam.statistics');

     // Mostrar el examen para realizar (reintentar)
    Route::get('/exam/{category}/{examId}/take', [ExamController::class, 'take'])
     ->name('exam.take');

    // Procesar envío de respuestas y mostrar resultados corregidos
    Route::post('/exam/{category}/{examId}/submit', [ExamController::class, 'submit'])
     ->name('exam.submit');

    // Mostrar resultado tras enviar examen
    Route::get('/exam/{category}/{examId}/result', [ExamController::class, 'result'])
     ->name('exam.result');

    // Mostrar revision del examen
    Route::get('/exam/{category}/{revisionId}/revision', [ExamController::class, 'revision'])
     ->name('exam.revision');
});
