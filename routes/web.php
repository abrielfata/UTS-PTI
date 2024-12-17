<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('team', [Admin::class, 'index'])->name('team.index');
    Route::middleware('can:admin')->group(function () {
        Route::get('team/create', [Admin::class, 'create'])->name('team.create');
        Route::post('team', [Admin::class, 'control'])->name('team.control');
        Route::get('team/{team}/edit', [Admin::class, 'edit'])->name('team.edit');
        Route::put('team/{team}', [Admin::class, 'update'])->name('team.update');
        Route::delete('team/{team}', [Admin::class, 'destroy'])->name('team.destroy');
        Route::get('team/report', [Admin::class, 'report'])->name('team.report');
        Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('team/report', [Admin::class, 'report'])->name('team.report');
});
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('team/report', [Admin::class, 'report'])->name('team.report');
});

require __DIR__.'/auth.php';
