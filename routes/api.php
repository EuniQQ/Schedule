<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/event/{year}', [EventController::class, 'getEvents'])->name('event.index');
    Route::get('/calender/weather/des', [CalenderController::class, 'getWeatherDes'])->name('calender.weatherDes');
    Route::get('/calender/weather/type', [CalenderController::class, 'getWeatherType'])->name('calender.weatherType');
    Route::get('/daylySchedule/{id}', [CalenderController::class, 'show']);
    Route::get('/journal/photoModal/{id}', [JournalController::class, 'getPhotoDes']);
    Route::get('/journal/search', [JournalController::class, 'search'])->name('journal.search');
    Route::get('/journal/{id}', [JournalController::class, 'edit'])->name('journal.showEdit');
    Route::get('/journal/{year}/{month}', [JournalController::class, 'getJournal'])->name('journal.index');


    Route::post('/calender', [CalenderController::class, 'create'])->name('calender.create');
    Route::post('/calender/{id}', [CalenderController::class, 'update'])->name('calender.update');
    Route::post('/calender/style/{year}/{month}', [CalenderController::class, 'storeStyle'])->name('calender.styleAdd');
    Route::post('/calender/style/{id}', [CalenderController::class, 'updateStyle'])->name('calender.styleUpdate');
    Route::post('/journal', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/journal/{id}', [JournalController::class, 'update']);


    Route::delete('/calender/style/{id}', [CalenderController::class, 'destroyStyle'])->name('calender.styleDelete');
    Route::delete('/daylySchedule/{id}', [CalenderController::class, 'destroy'])->name('calender.destroy');
    Route::delete('/journal/singleImg/{id}', [JournalController::class, 'deleteImg'])->name('journal.singleDel');
    Route::delete('/journal/{id}', [JournalController::class, 'destroy'])->name('journal.destroy');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
