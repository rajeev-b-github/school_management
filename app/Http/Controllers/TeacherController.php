<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiResponseController;
use App\Models\TeacherProfile;
use App\Models\Address;
use App\Models\User;
use App\Models\Subject;
use App\Http\Requests\TeacherRequest;


class TeacherController extends Controller
{
    /**
     * This function create a teacher profile and save it to database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeacherRequest $request)
    {
        try {
            $userID = auth()->user()->id;
            $response[] = "";
            $user = User::find($userID);

            $teacherProfile = new TeacherProfile();
            $teacherProfile->user_id = $userID;
            $teacherProfile->profile_picture = $request->profile_picture;
            $teacherProfile->current_school = $request->current_school;
            $teacherProfile->previous_school = $request->previous_school;
            $teacherProfile->teacher_experience = $request->teacher_experience;
            $teacher = $user->teacherProfile()->save($teacherProfile);

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
                        $response = ApiResponseController::responseSuccess('Teacher Profile Created succesfully');
                    } else {
                        $response = ApiResponseController::responseFailed('Teacher Profil Creation Failed at Subjects');
                    }
                } else {
                    $response = ApiResponseController::responseFailed('Teacher Profile Creation Failed at address');
                }
            } else {
                $response = ApiResponseController::responseFailed('Teacher Profile Creation Failed at teacher_profiles');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        } catch (\Exception $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        }
        return $response;
    }

    /**
     * Returns the data for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        try {
            $userID = auth()->user()->id;
            $detail = User::where('id', $userID)->with(['teacherProfile', 'address', 'subject'])->get();
            $response[] = "";

            if (count($detail) > 0) {
                $response = ApiResponseController::responseSuccess('Record found successfully', $detail);
            } else {
                $response = ApiResponseController::responseNotFound('Record not found');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        } catch (\Exception $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        }
        return $response;
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



            $teacher = $user->teacherProfile()->update([
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
                        $response = ApiResponseController::responseSuccess('Teacher Profile Updated succesfully');
                    } else {
                        $response = ApiResponseController::responseFailed('Teacher Profile Updation Failed at Subjects');
                    }
                } else {
                    $response = ApiResponseController::responseFailed('Teacher Profile Updation Failed at address');
                }
            } else {
                $response = ApiResponseController::responseFailed('Teacher Profile Updation Failed at teachers_profiles');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        } catch (\Exception $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        }
        return $response;
    }
}
