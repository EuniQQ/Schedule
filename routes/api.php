<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalenderController;

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

// Route::middleware('auth:sanctum')->group(function () {
Route::get('/daylySchedule/{id}', [CalenderController::class, 'show']);
Route::delete('/daylySchedule/{id}', [CalenderController::class, 'destroy'])->name('calender.destroy');
Route::get('/user', function (Request $request) {
    return $request->user();
});
// });
