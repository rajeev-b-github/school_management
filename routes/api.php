<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;

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
    //Students
    Route::post('create_student', [StudentController::class, 'store']);
    Route::get('edit_student', [StudentController::class, 'edit']);
    Route::put('update_student', [StudentController::class, 'update']);

    //Teachers
    Route::post('create_teacher', [TeacherController::class, 'store']);
    Route::get('edit_teacher', [TeacherController::class, 'edit']);
    Route::put('update_teacher', [TeacherController::class, 'update']);
});


Route::group(
    ['prefix' => '/admin', 'middleware' => 'auth:api'],
    function () {
        Route::group(
            ['middleware' => 'isAdmin'],
            function () {
                // Delete User
                Route::put('delete_user/{user_id}', [AdminController::class, 'delete']);
                // Get Users for approval
                Route::get('get_users_for_approval/{user_type}', [AdminController::class, 'get_users_for_approval']);
                // Approve User
                Route::put('approve_user/{user_id}', [AdminController::class, 'approve_user']);
                // Approve All Users
                Route::put('approve_all_users', [AdminController::class, 'approve_all_users']);
                // Assign Teacher
                Route::put('assign_teacher', [AdminController::class, 'assign_teacher']);
                // Get all users by user_type
                Route::get('get_users/{user_type}', [AdminController::class, 'get_users']);
            }
        );
    }
);
