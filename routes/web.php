<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\TarefaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas do CRUD de tarefas (compatíveis com views e TarefaController)
    Route::prefix('tarefas')->name('tarefas.')->group(function () {
    Route::get('/', [\App\Http\Controllers\TarefaController::class, 'index'])->name('index');
    Route::get('/create', [\App\Http\Controllers\TarefaController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\TarefaController::class, 'store'])->name('store');
    Route::post('/generate', [\App\Http\Controllers\TarefaController::class, 'generate'])->name('generate');

    // AJAX / auxiliares (definidas antes das rotas com parâmetro para evitar captura de segmentos)
    Route::patch('/{tarefa}/status', [\App\Http\Controllers\TarefaController::class, 'updateStatus'])->name('status');
    // Comments
    Route::post('/{tarefa}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/{tarefa}/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/{tarefa}/modal-show', [\App\Http\Controllers\TarefaController::class, 'modalShow'])->name('modal.show');
    Route::get('/{tarefa}/modal-edit', [\App\Http\Controllers\TarefaController::class, 'modalEdit'])->name('modal.edit');
    Route::get('/modal-create', [\App\Http\Controllers\TarefaController::class, 'modalCreate'])->name('modal.create');
    Route::get('/partial-table', [\App\Http\Controllers\TarefaController::class, 'partialTable'])->name('partial.table');

    // Rotas que usam binding por parâmetro (deixe por último)
    Route::get('/{tarefa}', [\App\Http\Controllers\TarefaController::class, 'show'])->name('show');
    Route::get('/{tarefa}/edit', [\App\Http\Controllers\TarefaController::class, 'edit'])->name('edit');
    Route::put('/{tarefa}', [\App\Http\Controllers\TarefaController::class, 'update'])->name('update');
    Route::delete('/{tarefa}', [\App\Http\Controllers\TarefaController::class, 'destroy'])->name('destroy');
    });

    // Rotas de perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
