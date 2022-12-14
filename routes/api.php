<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// AUTH
Route::group([
    'middleware' => 'jwt.auth'
], function () {
    // Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'profile']);
});


// USERS
Route::group([
    'middleware' => ['jwt.auth', 'isSuperAdmin']
], function () {
    Route::post('/add_super_admin_role/{id}', [UserController::class, 'addSuperAdminRoleByIdUser']);
});

// BOOKS
Route::group([
    'middleware' => ['jwt.auth']
], function () {
    Route::post('/books', [BookController::class, 'createBook']);
    Route::put('/books/{id}', [BookController::class, 'updateBook']);
    Route::get('/books', [BookController::class, 'getAllBooks']);
});
