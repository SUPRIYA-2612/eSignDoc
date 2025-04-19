<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/', [DocumentController::class, 'dashboard'])->name('dashboard');

Route::get('/upload', [DocumentController::class, 'create'])->name('upload.form');
Route::post('/upload', [DocumentController::class, 'store'])->name('upload.store');

Route::get('/sign/{id}', [DocumentController::class, 'signForm'])->name('sign.form');
Route::post('/documents/{id}/submit-signature', [DocumentController::class, 'submitSignature'])->name('documents.submitSignature');

Route::get('/preview/{id}', [DocumentController::class, 'preview'])->name('preview');
Route::get('/download/{id}', [DocumentController::class, 'download'])->name('download');