<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Login
Route::post('login', [UserController::class, 'login']);
Route::get('login', [UserController::class, 'login'])->name('login');
//Register
Route::post('register', [UserController::class, 'register']);


//User Auth middleware
Route::middleware('auth:api')->group(function () {
    //Logout
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('get_id', [StudentController::class, 'get_id']);
    //Students
    Route::post('create_student', [StudentController::class, 'store']);
    // Route::get('edit_student/{user_id}', [StudentController::class, 'edit']);
    // Route::put('update_student', [StudentController::class, 'update']);

    //Teachers
    // Route::post('create_teacher', [TeacherController::class, 'store']);
    // Route::get('edit_teacher/{user_id}', [TeacherController::class, 'edit']);
    // Route::put('update_teacher', [TeacherController::class, 'update']);
});
