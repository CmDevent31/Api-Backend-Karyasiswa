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
Route::put('update-profile/{id}', [AuthController::class, 'update']);

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

// Galeri Sekolah routes
Route::post('galeri-sekolah', [GaleriSekolahController::class, 'store']);
Route::get('galeri-sekolah', [GaleriSekolahController::class, 'list']);
