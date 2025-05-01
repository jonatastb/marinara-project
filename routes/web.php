<?php

use App\Http\Controllers\AuditoriaDeFaturaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Home');
    })->name('dashboard');

    Route::get('/auditoria-de-fatura', [AuditoriaDeFaturaController::class, 'index'])->name('auditoria-de-fatura');
    Route::post('/processar', [AuditoriaDeFaturaController::class, 'importar'])->name('auditoria.processar');
    Route::get('/download', [AuditoriaDeFaturaController::class, 'download'])->name('auditoria.download');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
