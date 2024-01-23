<?php

use App\Http\Controllers\Api\CategoryController;
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

/* 
 | Remember that routes declared in api.php will automatically prepend the /api prefix, e.g.:
 | Route::get('/hello', ...)
 | axios.get('/api/hello')
*/

Route::get('/categories', [CategoryController::class, 'getAll']);
Route::get('/authors', [AuthorController::class, 'getAll']);

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});
*/