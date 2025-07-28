<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PerbaikanLaptopController;
use App\Http\Controllers\RiwayatLaptopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TandaTerimaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RepairController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Auth::routes();

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Laptop Management
    Route::resource('laptops', LaptopController::class);
    Route::post('laptops/{laptop}/upload-image', [LaptopController::class, 'uploadImage'])->name('laptops.upload-image');
    
    // User Management (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
        Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
    });

    // Route untuk perbaikan laptop
    Route::get('/laptops/{laptop}/perbaikan/create', [PerbaikanLaptopController::class, 'create'])->name('laptops.perbaikan.create');
    Route::post('/laptops/{laptop}/perbaikan', [PerbaikanLaptopController::class, 'store'])->name('laptops.perbaikan.store');
    Route::delete('/laptops/{laptop}/perbaikan/{repair}', [RepairController::class, 'destroy'])->name('laptops.perbaikan.destroy');

    // Route untuk riwayat perpindahan client
    Route::post('/laptops/{laptop}/riwayat', [RiwayatLaptopController::class, 'store'])->name('laptops.riwayat.store');
    Route::delete('/laptops/{laptop}/riwayat/{riwayat}', [RiwayatLaptopController::class, 'destroy'])
        ->name('laptops.riwayat.destroy');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes untuk Tanda Terima
    Route::post('/laptops/{laptop}/tanda-terima', [TandaTerimaController::class, 'store'])->name('laptops.tanda-terima.store');
    Route::delete('/laptops/{laptop}/tanda-terima/{id}', [TandaTerimaController::class, 'destroy'])->name('laptops.tanda-terima.destroy');
});

// Tambahkan di dalam group middleware auth dan admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('laporan')->group(function () {
    Route::get('/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('/download/excel/{type}', [LaporanController::class, 'downloadExcel'])->name('laporan.download.excel');
    Route::get('/download/pdf/{type}', [LaporanController::class, 'downloadPDF'])->name('laporan.download.pdf');
});

Route::prefix('reports')->group(function () {
    Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
    Route::get('/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/download/excel', [ReportController::class, 'downloadExcel'])->name('reports.download.excel');
    Route::get('/download/pdf', [ReportController::class, 'downloadPDF'])->name('reports.download.pdf');
});

Route::put('/laptops/{laptop}/update-kondisi', [LaptopController::class, 'updateKondisi'])
    ->name('laptops.update-kondisi');

Route::get('/laptops/get-last-number', [LaptopController::class, 'getLastAssetNumber'])->name('laptops.getLastAssetNumber');

Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');

Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');

// Route untuk perbaikan laptop
Route::prefix('laptops')->name('laptops.')->group(function () {
    Route::post('{laptop}/repair', [RepairController::class, 'store'])->name('repair.store');
    Route::delete('{laptop}/repair/{repair}', [RepairController::class, 'destroy'])->name('repair.destroy');
});
