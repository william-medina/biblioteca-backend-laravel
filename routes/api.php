<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;


Route::middleware('auth:sanctum')->group(function() {
    Route::get('/auth/me', [AuthController::class, 'getCurrentUser']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{isbn}', [BookController::class, 'update']);
    Route::delete('/books/{isbn}', [BookController::class, 'destroy']);
});


Route::get('/books/count', [BookController::class, 'getBookCount']);
Route::get('/books/random/{count}', [BookController::class, 'getRandomBooks'])->where('count', '[0-9]+');
Route::get('/books/location', [BookController::class, 'getLocationBooks']);
Route::get('/books/{sortBy?}', [BookController::class, 'getAllBooks'])->where('sortBy', 'title|author|publisher|publication_year|id'); 
Route::get('/books/search/{keyword}', [BookController::class, 'getBooksByKeyword']);
Route::get('/books/isbn/{isbn}', [BookController::class, 'getBookByISBN']);
Route::get('/books/location', [BookController::class, 'getLocationBooks']);

//Route::apiResource('/', BookController::class);


// Authentication
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/update-password', [AuthController::class, 'updatePassword']);

// Visualizacion de portadas
Route::get('covers/{filename}', function ($filename) {
    $path = storage_path("app/public/covers/{$filename}");

    // Verifica si el archivo existe en la ruta completa
    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});