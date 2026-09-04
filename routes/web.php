<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ModemController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ClientController;
use App\Models\Plan;

Route::redirect('/', '/login');

Route::get('/sidebard', function () {
    return view('layout.sidebard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [
            AdminDashboardController::class,
            'index'
        ])->name('dashboard');

    });

Route::middleware(['auth', 'role:super_admin,superadmin,admin,manager,user'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/compte', [AccountController::class, 'index'])->name('compte');
        Route::post('/compte', [AccountController::class, 'store'])->name('compte.store');
        Route::put('/compte/{user}', [AccountController::class, 'update'])->name('compte.update');
        Route::delete('/compte/{user}', [AccountController::class, 'destroy'])->name('compte.destroy');
        Route::post('/compte/{user}/reset-password', [AccountController::class, 'resetPassword'])->name('compte.reset-password');
        Route::get('/compte/export', [AccountController::class, 'export'])->name('compte.export');
        Route::get('/forfaits', [PlanController::class, 'index'])->name('forfaits');
        Route::post('/forfaits', [PlanController::class, 'store'])->name('forfaits.store');
        Route::put('/forfaits/{plan}', [PlanController::class, 'update'])->name('forfaits.update');
        Route::patch('/forfaits/{plan}/toggle', [PlanController::class, 'toggle'])->name('forfaits.toggle');
        Route::delete('/forfaits/{plan}', [PlanController::class, 'destroy'])->name('forfaits.destroy');
        Route::get('/client', [ClientController::class, 'index'])->name('client');
        Route::post('/client', [ClientController::class, 'store'])->name('client.store');
        Route::get('/wifi', [ModemController::class, 'index'])->name('wifi');
        Route::post('/wifi', [ModemController::class, 'store'])->name('wifi.store');
        Route::patch('/wifi/{modem}/toggle', [ModemController::class, 'toggle'])->name('wifi.toggle');
        Route::delete('/wifi/{modem}', [ModemController::class, 'destroy'])->name('wifi.destroy');
        Route::get('/stat', [AnalyticsController::class, 'index'])->name('stat');
        Route::get('/facturation', [BillingController::class, 'index'])->name('facturation');
        Route::post('/facturation', [BillingController::class, 'store'])->name('facturation.store');
        Route::patch('/facturation/{transaction}/toggle', [BillingController::class, 'toggle'])->name('facturation.toggle');
        Route::delete('/facturation/{transaction}', [BillingController::class, 'destroy'])->name('facturation.destroy');
        Route::get('/facturation/export', [BillingController::class, 'export'])->name('facturation.export');
        Route::get('/notification', [NotificationController::class, 'index'])->name('notification');
        Route::patch('/notification/{alert}/read', [NotificationController::class, 'markRead'])->name('notification.read');
        Route::post('/notification/read-all', [NotificationController::class, 'markAllRead'])->name('notification.read-all');
        Route::delete('/notification/{alert}', [NotificationController::class, 'destroy'])->name('notification.destroy');
        Route::patch('/notification/{alert}/approve-account', [NotificationController::class, 'approveAccount'])->name('notification.approve-account');
        Route::patch('/notification/{alert}/reject-account', [NotificationController::class, 'rejectAccount'])->name('notification.reject-account');
        Route::get('/parametre', [SettingsController::class, 'index'])->name('parametre');
        Route::put('/parametre', [SettingsController::class, 'update'])->name('parametre.update');
        Route::post('/parametre/reset', [SettingsController::class, 'reset'])->name('parametre.reset');

    });

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/dashboard', [
            ManagerDashboardController::class,
            'index'
        ])->name('dashboard');

    });

    Route::middleware([
    'auth',
    'role:admin,manager'
])->group(function () {

    Route::get('/reports', function () {
        return view('reports.index');
    });

});

Route::get('/souscription', function () {
    return response()
        ->view('authentication.souscription', [
            'plans' => Plan::latest()->get(),
        ])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
})->name('souscription');

Route::middleware(['auth'])->post('/souscription/acheter', [BillingController::class, 'purchasePlan'])->name('souscription.purchase');

 Route::get('/home', function () {
        return view('authentication.iclan');
    });
