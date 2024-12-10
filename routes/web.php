<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CommentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', [ProjectController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Rutas del profesor
    Route::resource('projects', ProjectController::class)->except([
        'store', 'show', 'update', 'destroy'
    ]);
});

Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');
Route::middleware(['auth'])->group(function () {
    // Ruta para ver todos los proyectos
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');

    // Ruta para crear un nuevo proyecto
    Route::get('/proyectos/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/proyectos', [ProjectController::class, 'store'])->name('projects.store');

    // Ruta para editar un proyecto
    Route::get('/proyectos/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/proyectos/{project}', [ProjectController::class, 'update'])->name('projects.update');

    // Ruta para eliminar un proyecto
    Route::delete('/proyectos/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::resource('projects', ProjectController::class);
});

Route::post('/projects/{project}/files', [\FileController::class, 'store'])->name('files.store');
Route::delete('/files/{file}', [\FileController::class, 'destroy'])->name('files.destroy');

Route::get('/projects/{id}/profesor', [ProjectController::class, 'showProfesor'])->name('projects.showprofesor');  // Para profesores
Route::post('/projects/{projectId}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::prefix('comments')->middleware('auth')->group(function() {
    Route::post('/{projectId}/store', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/{commentId}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/{commentId}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('files/download/{fileId}', [FileController::class, 'download'])->name('files.download');
