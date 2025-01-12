<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\IdentitasRumahController;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    // User Management Routes
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users.index');
        Route::post('users', 'store')->name('users.store');
        Route::put('users/{user}', 'update')->name('users.update');
        Route::delete('users/{user}', 'destroy')->name('users.destroy');
    });

    // Role Management Routes
    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('roles.index');
        Route::post('roles', 'store')->name('roles.store');
        Route::put('roles/{role}', 'update')->name('roles.update');
        Route::delete('roles/{role}', 'destroy')->name('roles.destroy');
    });

    // Identitas Rumah Routes
    Route::controller(IdentitasRumahController::class)->group(function () {
        Route::get('identitas-rumah', 'index')->name('identitas-rumah.index');
        Route::post('identitas-rumah', 'store')->name('identitas-rumah.store');
        Route::put('identitas-rumah/{identitasRumah}', 'update')->name('identitas-rumah.update');
        Route::delete('identitas-rumah/{identitasRumah}', 'destroy')->name('identitas-rumah.destroy');
        Route::get('identitas-rumah/{identitasRumah}/download', 'download')->name('identitas-rumah.download');
    });

    // Kartu Keluarga & Penduduk Routes
    Route::prefix('kartu-keluarga')->group(function () {
        // KK Routes
        Route::controller(KartuKeluargaController::class)->group(function () {
            Route::get('/', 'index')->name('kartu-keluarga.index');
            Route::post('/', 'store')->name('kartu-keluarga.store');
            Route::put('/{kartuKeluarga}', 'update')->name('kartu-keluarga.update');
            Route::delete('/{kartuKeluarga}', 'destroy')->name('kartu-keluarga.destroy');
        });

        // Anggota/Penduduk Routes (Nested)
        Route::controller(PendudukController::class)->group(function () {
            Route::get('/{id_kk}/anggota', 'index')->name('penduduk.index');
            Route::post('/{id_kk}/anggota', 'store')->name('penduduk.store');
            Route::put('/{id_kk}/anggota/{penduduk}', 'update')->name('penduduk.update');
            Route::delete('/{id_kk}/anggota/{penduduk}', 'destroy')->name('penduduk.destroy');
        });
    });
});