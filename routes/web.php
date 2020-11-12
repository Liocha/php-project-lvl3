<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DomainCheckController;
use App\Http\Controllers\HomeController;

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


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('domains/{id}/checks', [DomainCheckController::class, 'checks'])->name('checks');
Route::get('domains/{id}', [DomainController::class, 'show'])->name('show');
Route::post('domains', [DomainController::class, 'store'])->name('store');
Route::get('domains', [DomainController::class, 'index'])->name('index');
