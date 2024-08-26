<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasklist', [TaskController::class, 'index'])->name('tasklist');
Route::get('/gettask', [TaskController::class, 'getTask'])->name('getTask');
Route::post('/saveTask', [TaskController::class, 'saveTask'])->name('saveTask');
Route::post('/updateTask', [TaskController::class, 'updateTask'])->name('updateTask');
Route::post('/deleteTask', [TaskController::class, 'deleteTask'])->name('deleteTask');
