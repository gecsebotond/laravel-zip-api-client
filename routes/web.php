<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (!session()->has('api_token')) {
        return redirect('/login');
    }

    return view('dashboard');
})->name('dashboard');

Route::get('/counties', [\App\Http\Controllers\CountyController::class, 'index'])->name('counties.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
