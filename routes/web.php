<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/calender/{year?}/{month?}', [CalenderController::class, 'index'])->name('calender.index');
    Route::get('/journal/{year?}/{month?}', [JournalController::class, 'index'])->name('journal');
    Route::get('/monthlyEvent/{year?}', [EventController::class, 'index'])->name('event.index');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route::post('/calender/{id}', [CalenderController::class, 'update'])->name('calender.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
