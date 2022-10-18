<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\Student_profile;
use App\Models\Teacher_profile;
use App\Event\UserApproved;
use App\Event\TeacherAssignedToStudent;
use App\Notifications\PushNotificationToUser;
use Exception;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    use Notifiable;
    public function delete($id)
    {
        try {

            $response[] = "";
            $result = User::where('id', $id)
                ->delete();
            if ($result) {
                $response = ['result' => 'User deleted succesfully', 'status' => '200', 'Deleted User Id' => $id,];
            } else {
                $response = ['result' => 'User Not Found with Id : ' . $id, 'status' => '404',];
            }
            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400', 'UserId' => $id, 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        }
    }
    public function get_users_for_approval($userType)
    {
        try {
            $response[] = "";
            $users = "";
            if ($userType == 'Student') {
                $users = User::join('student_profiles', 'user_id', '=', 'users.id')
                    ->join('addresses', 'addresses.user_id', '=', 'student_profiles.user_id')
                    ->join('parents_details', 'parents_details.user_id', '=', 'student_profiles.user_id')
                    ->select('users.name', 'users.email', 'student_profiles.*', 'addresses.*', 'parents_details.*')
                    ->where('users.user_type', '=', $userType)
                    ->where('users.is_approved', '=', 0)
                    ->get();
            } else {
                $users = User::join('teacher_profiles', 'user_id', '=', 'users.id')
                    ->join('addresses', 'addresses.user_id', '=', 'teacher_profiles.user_id')
                    ->join('subjects', 'subjects.user_id', '=', 'teacher_profiles.user_id')
                    ->select('users.name', 'users.email', 'teacher_profiles.*', 'addresses.*', 'subjects.*')
                    ->where('users.user_type', '=', $userType)
                    ->where('users.is_approved', '=', 0)
                    ->get();
            }
            if (count($users) > 0) {
                $response = ['result' => count($users) . ' Users found', 'status' => '200', 'data' => $users,];
            } else {
                $response = ['result' => 'No record found', 'status' => '203', 'data' => $users,];
            }

            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400', 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        }
    }

    public function approve_user($id)
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
                $response = ['result' => 'User approved succesfully', 'status' => '200', 'Approved User Id' => $id,];
            } else {
                $response = [
                    'result' => 'User Not Found or user already approved with Id : ' . $id,
                    'status' => '404',
                ];
            }
            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400', 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        }
    }
    public function approve_all_users()
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
                $response = [
                    'result' => 'All users approved succesfully', 'status' => '200',
                    'No of users approved' => $result,
                ];
            } else {
                $response = ['result' => 'Users Not Found or users already approved with Id ', 'status' => '404',];
            }
            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400', 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        }
    }
    public function assign_teacher(Request $req)
    {
        try {

            $response[] = "";
            $student = User::where('id', $req->student_id)->where('user_type', 'Student')->get();
            $teacher = User::where('id', $req->teacher_id)->where('user_type', 'Teacher')->get();

            if (count($student) > 0) {

                $result = Student_profile::where('user_id', $req->student_id)
                    ->update(['assigned_teacher' => $teacher[0]->name]);

                if ($result) {

                    event(new TeacherAssignedToStudent($teacher[0], $student[0]));

                    $response = [
                        'result' => 'Assigned teacher succesfully',
                        'status' => '200',
                        'User_id' => $req->user_id,
                        'teacher_assigned' => $teacher[0]->name,
                    ];
                } else {
                    $response = [
                        'result' => 'Student has not created profile',
                        'status' => '208',
                        'User_id' => $req->user_id,
                    ];
                }
            } else {

                $response = [
                    'result' => 'Student with the id not found',
                    'status' => '208',
                    'User_id' => $req->user_id,
                ];
            }

            return response()->json($response, $response['status']);
        } catch (Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400', 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        }
    }
    public function get_users($usertype)
    {
        try {
            $response[] = "";
            $users = "";
            if ($usertype == 'student') {
                $users = User::where('user_type', 'Student')
                    ->with(['student_profile', 'address', 'parents_detail'])->get();
            } elseif ($usertype == 'teacher') {
                $users = User::where('user_type', 'Teacher')
                    ->with(['teacher_profile', 'address', 'subject'])->get();
            } else {
                return [
                    'result' => 'Invalid User type',
                    'status' => '203',
                    'data' => $users,
                ];
            }

            if ($users) {
                $response = [
                    'result' => ' Record found successfully',
                    'no_of_records' => count($users),
                    'status' => '200',
                    'data' => $users,
                ];
            } else {
                $response = [
                    'result' => 'Records Not Found',
                    'status' => '203',
                    'data' => $users,
                ];
            }
            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400', 'data' => $e,
            ];
            return response()->json($response, $response['status']);
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
