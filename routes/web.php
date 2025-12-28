<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderWizardController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/pricing', 'pricing')->name('pricing');

Route::redirect('/app', '/dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create'); // legacy (kept)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store'); // legacy (kept)
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // New: 5-step order wizard
    Route::post('/orders/wizard/start', [OrderWizardController::class, 'start'])->name('orders.wizard.start');
    Route::get('/orders/{order}/wizard/step-1', [OrderWizardController::class, 'step1'])->name('orders.wizard.step1');
    Route::post('/orders/{order}/wizard/step-1', [OrderWizardController::class, 'saveStep1'])->name('orders.wizard.step1.save');
    Route::get('/orders/{order}/wizard/step-2', [OrderWizardController::class, 'step2'])->name('orders.wizard.step2');
    Route::post('/orders/{order}/wizard/step-2', [OrderWizardController::class, 'saveStep2'])->name('orders.wizard.step2.save');
    Route::post('/orders/{order}/wizard/step-2/upload', [OrderWizardController::class, 'uploadStep2'])->name('orders.wizard.step2.upload');
    Route::get('/orders/files/{file}', [OrderFileController::class, 'download'])->name('orders.files.download');
    Route::delete('/orders/files/{file}', [OrderFileController::class, 'destroy'])->name('orders.files.destroy');
    Route::get('/orders/{order}/wizard/step-3', [OrderWizardController::class, 'step3'])->name('orders.wizard.step3');
    Route::post('/orders/{order}/wizard/step-3', [OrderWizardController::class, 'saveStep3'])->name('orders.wizard.step3.save');
    Route::get('/orders/{order}/wizard/step-4', [OrderWizardController::class, 'step4'])->name('orders.wizard.step4');
    Route::post('/orders/{order}/wizard/step-4/confirm', [OrderWizardController::class, 'confirmStep4'])->name('orders.wizard.step4.confirm');
    Route::get('/orders/{order}/wizard/step-5', [OrderWizardController::class, 'step5'])->name('orders.wizard.step5');
    Route::post('/orders/{order}/wizard/step-5', [OrderWizardController::class, 'submitStep5'])->name('orders.wizard.step5.submit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/review', [AdminOrderController::class, 'review'])->name('orders.review');
});

require __DIR__.'/auth.php';
