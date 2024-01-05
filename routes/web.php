<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tp2Controller;

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

Route::get('/', [Tp2Controller::class,'index'])->name('index');

Route::get('/create', function () {
    return view('create');
});
Route::post('/store', [Tp2Controller::class,'store']);

Route::get('/edit/{id}', [Tp2Controller::class,'edite']);
Route::put('/update', [Tp2Controller::class,'update']);
Route::get('/supprimer/{id}', [Tp2Controller::class,'supprimer']);
Route::get('/max', [Tp2Controller::class,'max']);
Route::get('/min', [Tp2Controller::class,'min']); 