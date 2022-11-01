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
    ['prefix' => '/admin', 'middleware' => ['auth:api', 'isAdmin']],
    function () {
        // Delete User
        Route::delete('delete_user/{user_id}', [AdminController::class, 'delete']);
        // Get Users for approval
        Route::get('get_users_for_approval/{user_type}', [AdminController::class, 'getUsersForApproval']);
        // Approve User
        Route::put('approve_user/{user_id}', [AdminController::class, 'approveUser']);
        // Approve All Users
        Route::put('approve_all_users', [AdminController::class, 'approveAllUsers']);
        // Assign Teacher
        Route::put('assign_teacher', [AdminController::class, 'assignTeacher']);
        // Get all users by user_type
        Route::get('get_users/{user_type}', [AdminController::class, 'getUsers']);
    }
);
