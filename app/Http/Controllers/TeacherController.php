<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher_profile;
use App\Models\Address;
use App\Models\User;
use App\Models\Parents_detail;
use App\Models\Subject;
use App\Http\Requests\TeacherRequest;

class TeacherController extends Controller
{
    public function list()
    {
        $details = User::find(2);
        dd($details);
    }
    public function store(TeacherRequest $request)
    {
        try {
            $userID = auth()->user()->id;
            $response[] = "";

            $result = Teacher_profile::insert([
                'user_id' => $userID,
                'profile_picture' => $request->profile_picture,
                'current_school' => $request->current_school,
                'previous_school' => $request->previous_school,
                'teacher_experience' => $request->teacher_experience,
            ]);

            if ($result) {
                $result1 = Address::insert([
                    'user_id' => $userID,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'pin_code' => $request->pin_code,
                ]);

                if ($result1) {
                    $result2 = Subject::insert([
                        'user_id' => $userID,
                        'subject_1' => $request->subject_1,
                        'subject_2' => $request->subject_2,
                        'subject_3' => $request->subject_3,
                        'subject_4' => $request->subject_4,
                        'subject_5' => $request->subject_5,
                        'subject_6' => $request->subject_6,
                    ]);

                    if ($result2) {
                        $response = [
                            'result' => 'User Created succesfully',
                            'status' => '200',
                            'UserId' => $userID,
                        ];
                    } else {
                        $response = [
                            'result' =>
                            'User Creation Failed at Subjects',
                            'status' => '203',
                            'UserId' => $userID,
                        ];
                    }
                } else {
                    $response = [
                        'result' => 'User Creation Failed at address',
                        'status' => '203',
                        'UserId' => $userID,
                    ];
                }
            } else {
                $response = [
                    'result' => 'User Creation Failed at teacher_profiles',
                    'status' => '203',
                    'UserId' => $userID,
                ];
            }
            return  $response;
        } catch (\Illuminate\Database\QueryException $e) {
            return
                [
                    'result' => 'Error Exception : Bad Request',
                    'status' => '400',
                    'UserId' => $userID,
                    'data' => $e,
                ];
        } catch (\Exception $e) {
            return
                [
                    'result' => 'Error Exception : Bad Request',
                    'status' => '400',
                    'UserId' => $userID,
                    'data' => $e,
                ];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        try {
            $userID = auth()->user()->id;
            $response[] = "";
            $users = Teacher_profile::join('users', 'id', '=', 'teacher_profiles.user_id')
                ->join(
                    'addresses',
                    'addresses.user_id',
                    '=',
                    'teacher_profiles.user_id'
                )
                ->join(
                    'subjects',
                    'subjects.user_id',
                    '=',
                    'teacher_profiles.user_id'
                )
                ->select(
                    'users.name',
                    'teacher_profiles.*',
                    'addresses.*',
                    'subjects.*'
                )
                ->where('users.user_type', '=', 'Teacher')
                ->where('teacher_profiles.user_id', '=', $userID)
                ->get();
            if (count($users) > 0) {
                $response = [
                    'result' => 'Record found successfully',
                    'status' => '200',
                    'UserId' => $userID,
                    'data' => $users,
                ];
            } else {
                $response = [
                    'result' => 'Record not found',
                    'status' => '404',
                    'UserId' => $userID,
                    'data' => $users,
                ];
            }
            return $response;
        } catch (\Illuminate\Database\QueryException $e) {
            return
                [
                    'result' => 'Error Exception : Bad Request',
                    'status' => '400',
                    'UserId' => $userID,
                    'data' => $e,
                ];
        } catch (\Exception $e) {
            return
                [
                    'result' => 'Error Exception : Bad Request',
                    'status' => '400',
                    'UserId' => $userID,
                    'data' => $e,
                ];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherRequest $request)
    {
        try {
            $response[] = "";
            $userID = auth()->user()->id;
            User::where('id', $userID)
                ->update([
                    'name' => $request->name,
                ]);
            Teacher_profile::where('user_id', $userID)
                ->update([
                    'profile_picture' => $request->profile_picture,
                    'current_school' => $request->current_school,
                    'previous_school' => $request->previous_school,
                    'teacher_experience' => $request->teacher_experience,
                ]);


            Address::where('user_id', $userID)
                ->update([
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'pin_code' => $request->pin_code,
                ]);


            Subject::where('user_id', $userID)
                ->update([
                    'subject_1' => $request->subject_1,
                    'subject_2' => $request->subject_2,
                    'subject_3' => $request->subject_3,
                    'subject_4' => $request->subject_4,
                    'subject_5' => $request->subject_5,
                    'subject_6' => $request->subject_6,
                ]);

            $response = [
                'result' => 'Record Updated successfully',
                'status' => '200',
                'UserId' => $userID,
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $response =
                [
                    'result' => 'Error Exception : Bad Request',
                    'status' => '400',
                    'UserId' => $userID,
                    'data' => $e,
                ];
        } catch (\Exception $e) {
            $response =
                [
                    'result' => 'Error Exception : Bad Request',
                    'status' => '400',
                    'UserId' => $userID,
                    'data' => $e,
                ];
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     try {

    //         $result =  Teacher_profile::where('user_id', $id)
    //             ->delete();
    //         if ($result) {
    //             return [
    //                 'result' => 'Data deleted succesfully',
    //                 'status' => '200',
    //             ];
    //         } else {
    //             return response()->json('User Not Found with Id : ' . $id, 404);
    //         }
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         //throw $th;
    //         return ['Error : ' . $e];
    //     }
    // }
}
