<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Event\UserApproved;
use App\Event\TeacherAssignedToStudent;
use Exception;
use App\Http\Controllers\Api\ApiResponseController;

class AdminController extends Controller
{
    use Notifiable;

    /**
     * This function delete the perticular user
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {

            $response[] = "";
            $user = User::find($id);
            if (!$user) {
                return ApiResponseController::responseNotFound('User Not Found with Id : ' . $id);
            }

            $user->delete();
            $response = ApiResponseController::responseSuccess('User deleted succesfully');
        } catch (\Exception $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        }
        return $response;
    }

    /**
     * This function returns all the unapproved users list
     *
     * @param  userType is student or teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersForApproval($userType)
    {
        try {
            $response[] = "";
            $users = "";
            if ($userType == 'student') {
                $users = User::join('student_profiles', 'user_id', '=', 'users.id')
                    ->join('addresses', 'addresses.user_id', '=', 'student_profiles.user_id')
                    ->join('parents_details', 'parents_details.user_id', '=', 'student_profiles.user_id')
                    ->select('users.name', 'users.email', 'student_profiles.*', 'addresses.*', 'parents_details.*')
                    ->where('users.user_type', '=', $userType)
                    ->where('users.is_approved', '=', 0)
                    ->get();
            } elseif ($userType == 'teacher') {
                $users = User::join('teacher_profiles', 'user_id', '=', 'users.id')
                    ->join('addresses', 'addresses.user_id', '=', 'teacher_profiles.user_id')
                    ->join('subjects', 'subjects.user_id', '=', 'teacher_profiles.user_id')
                    ->select('users.name', 'users.email', 'teacher_profiles.*', 'addresses.*', 'subjects.*')
                    ->where('users.user_type', '=', $userType)
                    ->where('users.is_approved', '=', 0)
                    ->get();
            } else {
                return ApiResponseController::responseNotFound('Invalid user type');
            }
            if (count($users) > 0) {
                $response = ApiResponseController::responseSuccess(count($users) . ' Users found', $users);
            } else {
                $response = ApiResponseController::responseNotFound('Users Not Found');
            }

            return $response;
        } catch (\Exception $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }
    /**
     * This function approves the perticular unapproved user
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveUser($id)
    {
        try {
            $response[] = "";
            $result = User::where('id', $id)
                ->where('is_approved', 0)
                ->update(['is_approved' => 1]);
            if ($result) {
                $user = User::where('id', $id)
                    ->where('is_approved', 1)->get();
                event(new UserApproved($user[0]));
                $response = ApiResponseController::responseSuccess('User approved succesfully');
            } else {
                $response = ApiResponseController::responseNotFound('User Not Found or user already approved');
            }
            return $response;
        } catch (\Exception $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }

    /**
     * This function approves all the unapproved users and
     * send email notification to all the approved users
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveAllUsers()
    {
        try {
            $response[] = "";
            $users = User::where('is_approved', 0)->where('user_type', '!=', 'admin')
                ->get();
            $result = User::where('is_approved', 0)->where('user_type', '!=', 'admin')
                ->update(['is_approved' => 1]);
            if ($result) {
                foreach ($users as $user) {

                    event(new UserApproved($user));
                }
                $response = ApiResponseController::responseSuccess('Users approved succesfully');
            } else {
                $response = ApiResponseController::responseNotFound('Users Not Found or users already approved');
            }
            return $response;
        } catch (\Exception $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }


    /**
     * This function assigns a teacher to a student and send email
     * notification and app notification to the assigned teacher
     * @param  int $req->student_id and $req->teacher_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignTeacher(Request $req)
    {
        try {

            $response[] = "";
            $student = User::where('id', $req->student_id)->where('user_type', 'Student')->get();
            $teacher = User::where('id', $req->teacher_id)->where('user_type', 'Teacher')->get();

            if (count($student) > 0) {

                $result = StudentProfile::where('user_id', $req->student_id)
                    ->update(['assigned_teacher' => $teacher[0]->name]);

                if ($result) {

                    event(new TeacherAssignedToStudent($teacher[0], $student[0]));
                    $response = ApiResponseController::responseSuccess('Assigned teacher succesfully by name ' .
                        $teacher[0]->name);
                } else {
                    $response = ApiResponseController::responseSuccess('Student has not created profile');
                }
            } else {
                $response = ApiResponseController::responseNotFound('Student not found');
            }

            return $response;
        } catch (Exception $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }

    /**
     * This function returns all the users list by userType
     *
     * @param  userType is student or teacher
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers($usertype)
    {
        try {
            $response[] = "";
            $users = "";
            if ($usertype == 'student') {
                $users = User::where('user_type', 'Student')
                    ->with(['studentProfile', 'address', 'parentsDetail'])->get();
            } elseif ($usertype == 'teacher') {
                $users = User::where('user_type', 'Teacher')
                    ->with(['teacherProfile', 'address', 'subject'])->get();
            } else {
                return ApiResponseController::responseNotFound('Invalid user type');
            }

            if ($users) {
                $response = ApiResponseController::responseSuccess(
                    count($users) . ' Record found successfully',
                    $users
                );
            } else {
                $response = ApiResponseController::responseNotFound('No record found');
            }
            return $response;
        } catch (\Exception $e) {
            return ApiResponseController::responseServerError($e->getMessage());
        }
    }
    // public function get_user($id)
    // {
    //     try {
    //         $users = DB::table('user_profiles')
    //             ->join('users', 'users.id', '=', 'user_profiles.user_id')
    //             ->join(
    //                 'addresses',
    //                 'addresses.user_id',
    //                 '=',
    //                 'user_profiles.user_id'
    //             )
    //             ->join(
    //                 'parents_details',
    //                 'parents_details.user_id',
    //                 '=',
    //                 'user_profiles.user_id'
    //             )
    //             ->select(
    //                 'users.*',
    //                 'user_profiles.*',
    //                 'addresses.*',
    //                 'parents_details.*'
    //             )
    //             //->where('role', '=', 'Student')
    //             ->where('user_profiles.user_id', '=', $id)
    //             ->get();
    //         if (count($users) > 0) {
    //             return [
    //                 'status' => '200',
    //                 'record found' => count($users),
    //                 $users,
    //             ];
    //         } else {
    //             return [
    //                 'status' => '203',
    //                 'record found' => count($users),
    //             ];
    //         }
    //     } catch (\Exception $e) {
    //         //throw $th;
    //         return ['Error : ' . $e];
    //     }
    // }

}
