<?php

namespace App\Http\Controllers;

use App\Models\Teacher_profile;
use App\Models\Address;
use App\Models\User;
use App\Models\Subject;
use App\Http\Requests\TeacherRequest;


class TeacherController extends Controller
{
    public function store(TeacherRequest $request)
    {
        try {
            $userID = auth()->user()->id;
            $response[] = "";
            $user = User::find($userID);

            $teacherProfile = new Teacher_profile();
            $teacherProfile->user_id = $userID;
            $teacherProfile->profile_picture = $request->profile_picture;
            $teacherProfile->current_school = $request->current_school;
            $teacherProfile->previous_school = $request->previous_school;
            $teacherProfile->teacher_experience = $request->teacher_experience;
            $teacher = $user->teacher_profile()->save($teacherProfile);

            if ($teacher) {
                $address = new Address();
                $address->user_id = $userID;
                $address->address_1 = $request->address_1;
                $address->address_2 = $request->address_2;
                $address->city = $request->city;
                $address->state = $request->state;
                $address->country = $request->country;
                $address->pin_code = $request->pin_code;
                $tAddress = $user->address()->save($address);
                if ($tAddress) {
                    $subject = new Subject();
                    $subject->user_id = $userID;
                    $subject->subject_1 = $request->subject_1;
                    $subject->subject_2 = $request->subject_2;
                    $subject->subject_3 = $request->subject_3;
                    $subject->subject_4 = $request->subject_4;
                    $subject->subject_5 = $request->subject_5;
                    $subject->subject_6 = $request->subject_6;
                    $tsubject = $user->subject()->save($subject);
                    if ($tsubject) {
                        $response = [
                            'result' => 'Teacher Profile Created succesfully',
                            'status' => '200',
                            'UserId' => $userID,
                        ];
                    } else {
                        $response = [
                            'result' =>
                            'Teacher Profil Creation Failed at Subjects',
                            'status' => '203',
                            'UserId' => $userID,
                        ];
                    }
                } else {
                    $response = [
                        'result' => 'Teacher Profile Creation Failed at address',
                        'status' => '203',
                        'UserId' => $userID,
                    ];
                }
            } else {
                $response = [
                    'result' => 'Teacher Profile Creation Failed at teacher_profiles',
                    'status' => '203',
                    'UserId' => $userID,
                ];
            }


            return response()->json($response, $response['status']);
        } catch (\Illuminate\Database\QueryException $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400',
                'UserId' => $userID, 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400',
                'UserId' => $userID, 'data' => $e,
            ];
            return response()->json($response, $response['status']);
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
            $detail = User::where('id', $userID)->with(['teacher_profile', 'address', 'subject'])->get();
            $response[] = "";

            if (count($detail) > 0) {
                $response = [
                    'result' => 'Record found successfully',
                    'status' => '200',
                    'UserId' => $userID,
                    'data' => $detail,
                ];
            } else {
                $response = [
                    'result' => 'Record not found',
                    'status' => '404',
                    'UserId' => $userID,
                    'data' => $detail,
                ];
            }
            return response()->json($response, $response['status']);
        } catch (\Illuminate\Database\QueryException $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400',
                'UserId' => $userID, 'data' => $e,
            ];
            return response()->json($response, $response['status']);
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400',
                'UserId' => $userID, 'data' => $e,
            ];
            return response()->json($response, $response['status']);
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
            $user = User::find($userID);
            $user->update(['name' => $request->name,]);



            $teacher = $user->teacher_profile()->update([
                'profile_picture' => $request->profile_picture,
                'current_school' => $request->current_school,
                'previous_school' => $request->previous_school,
                'teacher_experience' => $request->teacher_experience,
            ]);

            if ($teacher) {

                $tAddress = $user->address()->update([
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'pin_code' => $request->pin_code,
                ]);
                if ($tAddress) {

                    $tsubject = $user->subject()->update([
                        'subject_1' => $request->subject_1,
                        'subject_2' => $request->subject_2,
                        'subject_3' => $request->subject_3,
                        'subject_4' => $request->subject_4,
                        'subject_5' => $request->subject_5,
                        'subject_6' => $request->subject_6,
                    ]);

                    if ($tsubject) {
                        $response = [
                            'result' => 'Teacher Profile Updated succesfully',
                            'status' => '200',
                            'UserId' => $userID,
                        ];
                    } else {
                        $response = [
                            'result' =>
                            'Teacher Profile Updation Failed at Subjects',
                            'status' => '203',
                            'UserId' => $userID,
                        ];
                    }
                } else {
                    $response = [
                        'result' => 'Teacher Profile Updation Failed at address',
                        'status' => '203',
                        'UserId' => $userID,
                    ];
                }
            } else {
                $response = [
                    'result' => 'Teacher Profile Updation Failed at teacher_profiles',
                    'status' => '203',
                    'UserId' => $userID,
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400',
                'UserId' => $userID, 'data' => $e,
            ];
        } catch (\Exception $e) {
            $response = [
                'result' => 'Error Exception : Bad Request',
                'status' => '400',
                'UserId' => $userID, 'data' => $e,
            ];
        }
        return response()->json($response, $response['status']);
    }
}
