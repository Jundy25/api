<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebtorsController;
use App\Http\Controllers\InsertDataController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UthangController;


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




Route::prefix('/v1')->group(function(){
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/debtors', [DebtorsController::class, 'debtors']);
Route::post('/insertdebtors', [InsertDataController::class, 'store']);
Route::put('/updatedebtor/{id}', [DebtorsController::class, 'updateDebtor']);
Route::delete('/deletedebtor/{id}', [InsertDataController::class, 'destroy']);
Route::get('/items', [ItemController::class, 'items']);
Route::get('uthangs', [UthangController::class, 'uthangs']);
Route::get('/uthangs/{id}', [UthangController::class, 'getUthangsByDebtorId']);
Route::post('/addutang', [UthangController::class, 'addUthang']);
Route::put('/updateutang/{id}', [UthangController::class, 'updateUthang']);
Route::delete('/payutang/{u_id}', [UthangController::class, 'deleteUthang']);
});

