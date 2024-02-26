<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CarsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware" => "auth:sanctum"], function(){
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
});

Route::controller(CarsController::class)->group(function(){
    Route::get('/cars', 'index');
    Route::get('/cars/{id}', 'show');
    Route::get('/cars/search/{name}', 'search');
    Route::post('/cars', 'store');
    Route::post('cars/{id}', 'update');
    Route::delete('/cars/{id}', 'destroy');
});

Route::controller(CategoryController::class)->group(function(){
    Route::get('/category', 'index');
    Route::get('/category/{id}', 'show');
    Route::get('/category/search/{name}', 'search');
    Route::post('/category', 'store');
    Route::post('/category/{id}', 'update');
    Route::delete('/category/{id}', 'destroy');
});

Route::controller(NewsController::class)->group(function(){
    Route::get('/news', 'index');
    Route::get('/news/{id}', 'show');
    Route::get('/news/search/{name}', 'search');
    Route::post('/news', 'store');
    Route::post('news/{id}', 'update');
    Route::delete('/news/{id}', 'destroy');
});

Route::controller(PagesController::class)->group(function(){
    Route::get('/pages', 'index');
    Route::get('/pages/{id}', 'show');
    Route::get('/pages/search/{name}', 'search');
    Route::post('/pages', 'store');
    Route::post('pages/{id}', 'update');
    Route::delete('/pages/{id}', 'destroy');
});

Route::controller(ProductsController::class)->group(function(){
    Route::get('/products', 'index');
    Route::get('/products/{id}', 'show');
    Route::get('/products/search/{name}', 'search');
    Route::post('/products', 'store');
    Route::post('products/{id}', 'update');
    Route::delete('/products/{id}', 'destroy');
});

Route::controller(RentalController::class)->group(function(){
    Route::get('/rental', 'index');
    Route::get('/rental/{id}', 'show');
    Route::get('/rental/search/{name}', 'search');
    Route::get('/rental/user/{id}', 'getUserRental');
    Route::post('/rental', 'store');
    Route::post('rental/{id}', 'update');
    Route::delete('/rental/{id}', 'destroy');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

