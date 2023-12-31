<?php

use App\Http\Controllers\SummeryController;
use App\Http\Controllers\TemplateController;
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

Route::view('/', 'welcome');
Route::view('/t', 'app');

Route::get('/orders-summery', [SummeryController::class, 'index']);
Route::get('/weekly-orders-summery', [SummeryController::class, 'weekly']);
Route::get('/orders', [TemplateController::class, 'orders']);
Route::get('/invoice/{id}', [TemplateController::class, 'invoice'])->name('invoice');
