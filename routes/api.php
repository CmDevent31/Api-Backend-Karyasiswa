<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EkskulController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\TableCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::put('update-profile/{id}', [AuthController::class, 'update']);

});

// routes/api.php





Route::get('/list',[TableCategoryController::class,'index']);
Route::post('/store',[TableCategoryController::class,'create']);
Route::post('/update/{id}',[TableCategoryController::class,'update']);
Route::delete('/destroy/{id}',[TableCategoryController::class,'destroy']);

Route::get('/show',[ArticleController::class,'index']);
Route::get('/detail/{id}',[ArticleController::class,'detail']);
Route::post('/create',[ArticleController::class,'add']);
Route::post('/renew/{id}',[ArticleController::class,'update']);
Route::delete('/delete/{id}',[ArticleController::class,'destroy']);


Route::get('/listcomment',[CommentController::class,'index']);
Route::post('/add',[CommentController::class,'create']);
Route::put('/deletecomment/{id}',[CommentController::class,'destroy']);

Route::post('/addproduk',[ProductController::class,'add']);
Route::get('/listproduk',[ProductController::class,'index']);
Route::get('/detailproduk/{id}',[ProductController::class,'detail']);
Route::post('/updateproduk/{id}',[ProductController::class,'update']);
Route::put('/deleteproduk/{id}',[ProductController::class,'destroy']);

Route::post('/membuatstock',[ProductStockController::class,'add']);
Route::get('/stocklist',[ProductStockController::class,'index']);
Route::post('/updatestock/{id}',[ProductStockController::class,'update']);

Route::post('/addekskul',[EkskulController::class,'store']);
Route::get('/listekskul',[EkskulController::class,'list']);
Route::post('/updateekskul/{id}',[EkskulController::class,'update']);

Route::get('/listevent',[EventsController::class,'list']);
Route::post('/addevent',[EventsController::class,'store']);
