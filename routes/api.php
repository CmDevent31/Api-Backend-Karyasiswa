<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TableCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\EkskulController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GaleriSekolahController;

// ...

// Authentication routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('update-profile/{id}', [AuthController::class, 'update']);

// Table Category routes
Route::get('table-categories', [TableCategoryController::class, 'index']);
Route::post('table-categories', [TableCategoryController::class, 'create']);
Route::put('table-categories/{id}', [TableCategoryController::class, 'update']);
Route::delete('table-categories/{id}', [TableCategoryController::class, 'destroy']);

// Article routes
Route::get('articles', [ArticleController::class, 'index']);
Route::get('articles/{id}', [ArticleController::class, 'detail']);
Route::post('articles', [ArticleController::class, 'add']);
Route::put('articles/{id}', [ArticleController::class, 'update']);
Route::delete('articles/{id}', [ArticleController::class, 'destroy']);

// Comment routes
Route::get('comments', [CommentController::class, 'index']);
Route::post('comments', [CommentController::class, 'create']);
Route::put('comments/{id}', [CommentController::class, 'destroy']);

// Product routes
Route::post('products', [ProductController::class, 'add']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'detail']);
Route::post('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);

// Product Stock routes
Route::post('product-stock', [ProductStockController::class, 'add']);
Route::get('product-stock', [ProductStockController::class, 'index']);
Route::post('product-stock/{id}', [ProductStockController::class, 'update']);

// Ekskul routes
Route::post('ekskuls', [EkskulController::class, 'store']);
Route::get('ekskuls', [EkskulController::class, 'list']);
Route::post('ekskuls/{id}', [EkskulController::class, 'update']);

// Events routes
Route::get('events', [EventsController::class, 'list']);
Route::post('events', [EventsController::class, 'store']);

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

Route::post('/updateekskul/{id}',[EkskulController::class,'update']);

Route::get('/listevent',[EventsController::class,'list']);
Route::post('/addevent',[EventsController::class,'store']);

Route::post('/addgaleri',[GaleriSekolahController::class,'store']);
Route::get('/listgaleri',[GaleriSekolahController::class,'list']);

// Route::middleware('auth:api')->get('/listekskul', [EkskulController::class, 'list']);
Route::get('/listekskul', [EkskulController::class, 'getListEkskul']);
Route::get('/listekskul2', [EkskulController::class, 'list']);

Route::get('/database-url', function () {
    return env('DATABASE_URL');
});
