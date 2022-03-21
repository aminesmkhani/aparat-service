<?php

use App\Http\Controllers\AparatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[AparatController::class,'index']);
Route::get('/login',[AparatController::class,'login']);
Route::post('/upload',[AparatController::class,'upload']);
Route::get('/delete',[AparatController::class,'delete']);
