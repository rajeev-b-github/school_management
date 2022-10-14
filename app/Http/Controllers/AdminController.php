<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use App\Models\User_profile;

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
            return $response;
        } catch (\Exception $e) {
            return
                ['result' => 'Error Exception : Bad Request', 'status' => '400', 'UserId' => $id, 'data' => $e,];
        }
    }
    public function get_users_for_approval($userType)
    {
        try {
            $response[] = "";
            $users = "";
            if ($userType == 'Student') {
                $users = User::join('user_profiles', 'user_id', '=', 'users.id')
                    ->join('addresses', 'addresses.user_id', '=', 'user_profiles.user_id')
                    ->join('parents_details', 'parents_details.user_id', '=', 'user_profiles.user_id')
                    ->select('users.name', 'users.email', 'user_profiles.*', 'addresses.*', 'parents_details.*')
                    ->where('users.user_type', '=', $userType)
                    ->where('users.is_approved', '=', 0)
                    ->get();
            } else {
                $users = User::join('user_profiles', 'user_id', '=', 'users.id')
                    ->join('addresses', 'addresses.user_id', '=', 'user_profiles.user_id')
                    ->join('subjects', 'subjects.user_id', '=', 'user_profiles.user_id')
                    ->select('users.name', 'users.email', 'user_profiles.*', 'addresses.*', 'subjects.*')
                    ->where('users.user_type', '=', $userType)
                    ->where('users.is_approved', '=', 0)
                    ->get();
            }
            if (count($users) > 0) {
                $response = ['result' => count($users) . ' Users found', 'status' => '200', 'data' => $users,];
            } else {
                $response = ['result' => 'No record found', 'status' => '203', 'data' => $users,];
            }

            return $response;
        } catch (\Exception $e) {
            return
                ['result' => 'Error Exception : Bad Request', 'status' => '400', 'data' => $e,];
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
                //$this->sendNotificationToUser($id);
                //event(new UserApproved($id));
                $response = ['result' => 'User approved succesfully', 'status' => '200', 'Approved User Id' => $id,];
            } else {
                $response = ['result' => 'User Not Found or user already approved with Id : ' . $id, 'status' => '404',];
            }
            return $response;
        } catch (\Exception $e) {
            return  ['result' => 'Error Exception : Bad Request', 'status' => '400', 'data' => $e,];
        }
    }
    public function approve_all_users()
    {
        try {
            $response[] = "";
            $users = User::where('is_approved', 0)
                ->get();
            $result = User::where('is_approved', 0)
                ->update(['is_approved' => 1]);
            if ($result) {
                foreach ($users as $user) {
                    //event(new UserApproved($user->user_id));
                    //$this->sendNotificationToUser($user->user_id);
                }
                $response = [
                    'result' => 'All users approved succesfully', 'status' => '200',
                    'No of users approved' => $result,
                ];
            } else {
                $response = ['result' => 'Users Not Found or users already approved with Id ', 'status' => '404',];
            }
        } catch (\Exception $e) {
            return ['result' => 'Error Exception : Bad Request', 'status' => '400', 'data' => $e,];
        }
    }
    public function assign_teacher(Request $req)
    {
        try {
            $response[] = "";
            $user = User::where('id', $req->user_id)->where('user_type', 'Student')->get();
            if ($user) {
                $result = User_profile::where('user_id', $req->user_id)
                    ->update(['assigned_teacher' => $req->assigned_teacher]);
                if ($result) {

                    // $this->sendNotificationToTeacher(
                    //     $req->assigned_teacher_id,
                    //     $req->user_id
                    // );
                    //event(new TeacherAssignedToStudent($req->assigned_teacher_id,$req->user_id));

                    $response = [
                        'result' => 'Data assigned teacher succesfully',
                        'status' => '200',
                        'User_id' => $req->user_id,
                        'teacher_assigned' => $req->assigned_teacher,
                    ];
                } else {
                    $response = [
                        'result' => 'User has not created profile',
                        'status' => '208',
                        'User_id' => $req->user_id,
                    ];
                }
            } else {

                $response = [
                    'result' => 'User Not a Student',
                    'status' => '208',
                    'User_id' => $req->user_id,
                ];
            }

            return $response;
        } catch (\Exception $e) {
            return ['result' => 'Error Exception : Bad Request', 'status' => '400', 'data' => $e,];
        }
    }
    // public function get_users($user_type)
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
    //             ->where('role', '=', $user_type)
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
    //         return ['Error : ' . $e];
    //     }
    // }
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
    // function sendNotificationToTeacher($teacherId, $StudentId)
    // {
    //     try {
    //         $teacher_data = DB::table('users')
    //             ->select('email', 'name')
    //             ->where('id', $teacherId)
    //             ->get();
    //         $student_data = DB::table('users')
    //             ->select('name')
    //             ->where('id', $StudentId)
    //             ->get();
    //         $mailData = [
    //             'name' => $teacher_data[0]->name,
    //             'body' => 'Meet your new student : ' . $student_data[0]->name,
    //             'thanks' => 'Thank you',
    //         ];

    //         Notification::route('mail', $teacher_data[0]->email)->notify(
    //             new AssignTeacherNotification($mailData)
    //         );
    //     } catch (\Exception $e) {
    //         //throw $th;
    //         return ['Error : ' . $e];
    //     }

    //     //return $mailData;

    //     // dd('notification has been sent!');
    // }

    // function sendNotificationToUser($Id)
    // {
    //     try {
    //         $user_data = DB::table('users')
    //             ->select('email', 'name')
    //             ->where('id', $Id)
    //             ->get();

    //         $mailData = [
    //             'name' => $user_data[0]->name,
    //             'body' => 'Your profile has been approved. ',
    //             'thanks' => 'Thank you',
    //         ];

    //         Notification::route('mail', $user_data[0]->email)->notify(
    //             new UserApprovalNotification($mailData)
    //         );
    //     } catch (\Exception $e) {
    //         //throw $th;
    //         return ['Error : ' . $e];
    //     }
    // }
}
