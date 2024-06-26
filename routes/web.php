<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\SpendingController;
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

Route::get('/register', function () {
    return view('register');
})->middleware(['auth', 'verified'])->name('register');


Route::middleware('auth')->group(function () {
    Route::get('/calender/{year?}/{month?}', function () {
        return view('content.calender');
    })->name('calender');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/journal/{year?}/{month?}', [JournalController::class, 'index'])->name('journal');
    Route::get('/monthlyEvent/{year?}', [EventController::class, 'index'])->name('event.index');
    Route::get('/income/{year?}', [IncomeController::class, 'index'])->name('income.index');
    Route::get('/spending', [SpendingController::class, 'index'])->name('spending.index');

    Route::post('/income/npo', [IncomeController::class, 'createNpo'])->name('income.createNpo');
    Route::post('/income', [IncomeController::class, 'createIncome'])->name('income.create');
    Route::post('/spending/cash', [SpendingController::class, 'createCash'])->name('cash.create');
    Route::post('/spending/card', [SpendingController::class, 'createCard'])->name('card.create');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
