<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\CalendarController;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk menangani redirect setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
});

// Test WhatsApp Route (remove in production)
Route::get('/test-whatsapp/{phone}/{message}', function($phone, $message, WhatsAppService $whatsappService) {
    $result = $whatsappService->sendMessage($phone, $message);
    return response()->json([
        'success' => $result,
        'message' => $result ? 'Message sent successfully' : 'Failed to send message',
        'phone' => $phone,
        'text' => $message
    ]);
})->middleware('auth');

// Dashboard route with role-based redirection
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if (!$user->is_approved) { // sesuaikan dengan field di tabel users
        return redirect()->route('pending-approval');
    }

    return redirect()->route('user.dashboard');
})->name('dashboard');

// ----------------------
// Admin routes
// ----------------------
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('users.approve');
        Route::post('/users/{user}/confirm-payment', [AdminController::class, 'confirmPayment'])->name('users.confirm-payment');
        Route::post('/users/{user}/reject-payment', [AdminController::class, 'rejectPayment'])->name('users.reject-payment');

        Route::get('/payment-settings', [AdminController::class, 'paymentSettings'])->name('payment-settings');
        Route::post('/payment-settings', [AdminController::class, 'updatePaymentSettings'])->name('payment-settings.update');
    });

// ----------------------
// User routes
// ----------------------
Route::middleware(['auth', 'approved'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        // Family Tree Routes
        Route::get('/family', [UserController::class, 'familyIndex'])->name('family.index');
        Route::get('/family/create', [UserController::class, 'familyCreate'])->name('family.create');
        Route::post('/family', [UserController::class, 'familyStore'])->name('family.store');
        Route::get('/family/{family}', [UserController::class, 'familyShow'])->name('family.show');
        Route::get('/family/{family}/edit', [UserController::class, 'familyEdit'])->name('family.edit');
        Route::put('/family/{family}', [UserController::class, 'familyUpdate'])->name('family.update');
        Route::delete('/family/{family}', [UserController::class, 'familyDestroy'])->name('family.destroy');

        // Family Member Routes
    Route::post('/family/{family}/members', [FamilyController::class, 'store'])->name('family.members.store');
    Route::get('/family/{family}/members/{member}/edit', [FamilyController::class, 'edit'])->name('family.members.edit');
    Route::put('/family/{family}/members/{member}', [FamilyController::class, 'update'])->name('family.members.update');
    Route::delete('/family/{family}/members/{member}', [FamilyController::class, 'destroy'])->name('family.members.destroy');

    // Company (GroupMember) Routes
    Route::post('/family/{family}/company-members', [\App\Http\Controllers\GroupMemberController::class, 'store'])->name('family.company.members.store');
    Route::get('/family/{family}/company-members/{member}/edit', [\App\Http\Controllers\GroupMemberController::class, 'edit'])->name('family.company.members.edit');
    Route::put('/family/{family}/company-members/{member}', [\App\Http\Controllers\GroupMemberController::class, 'update'])->name('family.company.members.update');
    Route::delete('/family/{family}/company-members/{member}', [\App\Http\Controllers\GroupMemberController::class, 'destroy'])->name('family.company.members.destroy');

        // Export Routes
        Route::get('/family/{family}/export/pdf', [FamilyController::class, 'exportPdf'])->name('family.export.pdf');

        // NIK-based Family Connection Routes
        Route::get('/family/{family}/members/{member}/suggestions', [FamilyController::class, 'getFamilySuggestions'])->name('family.members.suggestions');
        Route::post('/family/{family}/members/{member}/connect', [FamilyController::class, 'connectFamily'])->name('family.members.connect');

        // Pohon Keluarga (Tree) Route
        Route::get('/family-tree', [UserController::class, 'familyTree'])->name('family.tree');
    // Calendar Route
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    });

// ----------------------
// Payment Proof routes
// ----------------------
Route::middleware('auth')->group(function () {
    Route::get('/payment-proof/upload', [PaymentProofController::class, 'show'])->name('payment-proof.upload');
    Route::post('/payment-proof/upload', [PaymentProofController::class, 'upload'])->name('payment-proof.store');
    Route::get('/payment-proof/view', [PaymentProofController::class, 'view'])->name('payment-proof.view');
    Route::delete('/payment-proof', [PaymentProofController::class, 'delete'])->name('payment-proof.delete');
});

// ----------------------
// Pending approval route
// ----------------------
Route::middleware('auth')->get('/pending-approval', [UserController::class, 'pendingApproval'])->name('pending-approval');

// ----------------------
// Profile routes
// ----------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
