<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\DebtorsController;
use App\Http\Controllers\InsertDataController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UthangController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ResetPassController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LimitController;
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
Route::middleware('auth:sanctum')->post('/reset-password', [UserDataController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->get('/user-data', [UserDataController::class, 'getUserData']);
Route::middleware('auth:sanctum')->get('/userdata', [UserDataController::class, 'UserData']);

Route::get('/items', [ItemController::class, 'items']);
Route::post('/insert_items', [ItemController::class, 'addItem']);
Route::put('/edit_item/{item_id}', [ItemController::class, 'editItem']);
Route::delete('/delete_item/{item_id}', [ItemController::class, 'deleteItem']);
Route::get('/sales', [SaleController::class, 'sales']);
Route::get('/transactions', [TransactionController::class, 'transactions']);
Route::get('/viewtransactions/{id}', [TransactionController::class, 'getTransactionsByDebtorId']);
Route::get('/usertransactions/{id}', [TransactionController::class, 'getUserTransactions']);
Route::post('/uploadImage', [ImageController::class, 'uploadImage']);
Route::get('/getImage/{d_id}', [ImageController::class, 'getImage']);


Route::get('/debtors', [DebtorsController::class, 'debtors']);
Route::get('/debtor/{id}', [DebtorsController::class, 'debtor']);
Route::get('/balance/{id}', [DebtorsController::class, 'calculateValues']);
Route::post('/insertdebtors', [InsertDataController::class, 'store']);
Route::put('/updatedebtor/{id}', [DebtorsController::class, 'updateDebtor']);
Route::delete('/deletedebtor/{id}', [InsertDataController::class, 'destroy']);
Route::put('/datapayment/{id}', [DebtorsController::class, 'dataPayment']);
Route::put('/updatedataamount/{id}', [DebtorsController::class, 'updateDataAmount']);
Route::put('/checkbalance/{id}', [DebtorsController::class, 'checkBalance']);


Route::get('uthangs', [UthangController::class, 'uthangs']);
Route::get('/uthangs/{id}', [UthangController::class, 'getUthangsByDebtorId']);
Route::post('/addutang', [UthangController::class, 'addUthang']);
Route::put('/updateutang/{id}', [UthangController::class, 'updateUthang']);
Route::delete('/payutang/{u_id}', [UthangController::class, 'deleteUthang']);
Route::delete('/deleteutangs/{id}', [DebtorsController::class, 'deleteUtangs']);
Route::get('/overAllLimit', [LimitController::class, 'inventoryLimit']);
Route::get('/debtorLimit', [LimitController::class, 'limitByDebtor']);
Route::get('/interest', [LimitController::class, 'interest']);
Route::put('/updatelimit', [LimitController::class, 'update']);
});

