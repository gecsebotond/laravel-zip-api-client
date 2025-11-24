<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\PlaceController;

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
Route::get('/places', [\App\Http\Controllers\PlaceController::class, 'index'])->name('places.index');
Route::get('/places/{county}/initials', function ($county) {
    $token = session('api_token');

    $response = Http::withToken($token)
        ->get("http://localhost:8000/api/counties/$county/abc");

    return $response->json();
});

Route::get('/places/{county}/initials/{letter}', function ($county, $letter) {
    $token = session('api_token');

    $response = Http::withToken($token)
        ->get("http://localhost:8000/api/counties/$county/abc/$letter");

    return $response->json();
});


Route::get('/counties/{county}/download-xml', [CountyController::class, 'downloadXml'])
     ->name('counties.downloadXml');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/counties/create', [CountyController::class, 'create'])->name('counties.create');
    Route::post('/counties', [CountyController::class, 'store'])->name('counties.store');
    Route::get('/counties/{id}/edit', [CountyController::class, 'edit'])->name('counties.edit');
    Route::put('/counties/{id}', [CountyController::class, 'update'])->name('counties.update');
    Route::delete('/counties/{id}', [CountyController::class, 'destroy'])->name('counties.destroy');


    Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create');
    Route::post('/places', [PlaceController::class, 'store'])->name('places.store');
    Route::get('/places/{id}/edit', [PlaceController::class, 'edit'])->name('places.edit');
    Route::put('/places/{id}', [PlaceController::class, 'update'])->name('places.update');
    Route::delete('/places/{id}', [PlaceController::class, 'destroy'])->name('places.destroy');
});

require __DIR__.'/auth.php';
