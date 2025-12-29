<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderChatController;
use App\Http\Controllers\OrderWizardController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\SupportChatController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PricingController as AdminPricingController;
use App\Http\Controllers\Admin\MaintenanceInvoiceController as AdminMaintenanceInvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/pricing', 'pricing')->name('pricing');

Route::redirect('/app', '/dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create'); // legacy (kept)
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store'); // legacy (kept)
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/chat', [OrderChatController::class, 'store'])->name('orders.chat.store');

    Route::get('/support/chat', [SupportChatController::class, 'show'])->name('support.chat');
    Route::post('/support/chat', [SupportChatController::class, 'store'])->name('support.chat.store');

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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthenticatedSessionController::class, 'store']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
        Route::get('/members/{user}', [AdminMemberController::class, 'show'])->name('members.show');
        Route::post('/members/{user}/verify-email', [AdminMemberController::class, 'markVerified'])->name('members.verify_email');

        Route::get('/pricing', [AdminPricingController::class, 'index'])->name('pricing.index');
        Route::post('/pricing', [AdminPricingController::class, 'save'])->name('pricing.save');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/review', [AdminOrderController::class, 'review'])->name('orders.review');
        Route::post('/orders/{order}/payment/verify', [AdminOrderController::class, 'markPaymentVerified'])->name('orders.payment.verify');
        Route::post('/orders/{order}/work', [AdminOrderController::class, 'updateWork'])->name('orders.work.update');

        Route::get('/maintenance', [AdminMaintenanceInvoiceController::class, 'index'])->name('maintenance.index');
    });
});

require __DIR__.'/auth.php';
