<?php

use App\Http\Controllers\Controller;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/first-endpoint', [Controller::class, 'handleGetRequest']);

Route::post('/post-endpoint', [Controller::class, 'handlePostRequest']);

Route::get('/work-day', [Controller::class, 'isWorkDay']);

Route::post('/trvani', [Controller::class, 'getDuration']);
