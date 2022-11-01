<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiResponseController;
use App\Http\Requests\StudentRequest;
use App\Models\StudentProfile;
use App\Models\Address;
use App\Models\User;
use App\Models\ParentsDetail;


class StudentController extends Controller
{
    /**
     * This function create a student profile and save it to database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  profile parameters
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        try {
            $response[] = "";
            $userID = auth()->user()->id;
            $user = User::find($userID);


            $studentProfile = new StudentProfile();
            $studentProfile->user_id = $userID;
            $studentProfile->profile_picture = $request->profile_picture;
            $studentProfile->current_school = $request->current_school;
            $studentProfile->previous_school = $request->previous_school;
            $student = $user->studentProfile()->save($studentProfile);
            if ($student) {
                $address = new Address();
                $address->user_id = $userID;
                $address->address_1 = $request->address_1;
                $address->address_2 = $request->address_2;
                $address->city = $request->city;
                $address->state = $request->state;
                $address->country = $request->country;
                $address->pin_code = $request->pin_code;
                $stAddress = $user->address()->save($address);
                if ($stAddress) {
                    $parentsDetail = new ParentsDetail();
                    $parentsDetail->user_id = $userID;
                    $parentsDetail->father_name = $request->father_name;
                    $parentsDetail->mother_name = $request->mother_name;
                    $parentsDetail->father_occupation = $request->father_occupation;
                    $parentsDetail->mother_occupation = $request->mother_occupation;
                    $parentsDetail->father_contact_no = $request->father_contact_no;
                    $parentsDetail->mother_contact_no = $request->mother_contact_no;
                    $parents = $user->parentsDetail()->save($parentsDetail);
                    if ($parents) {
                        $response = ApiResponseController::responseSuccess('Student Profile Created succesfully');
                    } else {
                        $response = ApiResponseController::responseFailed('Student Profile Creation Failed at parents detail');
                    }
                } else {
                    $response = ApiResponseController::responseFailed('Student Profile Creation Failed at address');
                }
            } else {
                $response = ApiResponseController::responseFailed('Student Profile Creation Failed at student_profiles');
            }
        } catch (\Illuminate\Database\QueryException $e) {
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
            $response[] = "";
            $userID = auth()->user()->id;
            $detail = User::where('id', $userID)->with(['studentProfile', 'address', 'parentsDetail'])->get();
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
    public function update(StudentRequest $request)
    {
        try {

            $response[] = "";
            $userID = auth()->user()->id;
            $user = User::find($userID);
            $user->update(['name' => $request->name,]);

            $student = $user->studentProfile()->update([
                'profile_picture' => $request->profile_picture,
                'current_school' => $request->current_school,
                'previous_school' => $request->previous_school,
                'assigned_teacher' => $request->assigned_teacher,
            ]);
            if ($student) {
                $address = $user->address()->update([
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                    'pin_code' => $request->pin_code,
                ]);
                if ($address) {

                    $parents = $user->parentsDetail()->update([
                        'father_name' => $request->father_name,
                        'mother_name' => $request->mother_name,
                        'father_occupation' => $request->father_occupation,
                        'mother_occupation' => $request->mother_occupation,
                        'father_contact_no' => $request->father_contact_no,
                        'mother_contact_no' => $request->mother_contact_no,
                    ]);
                    if ($parents) {
                        $response = ApiResponseController::responseSuccess('Student Profile Updated succesfully');
                    } else {
                        $response = ApiResponseController::responseFailed('Student Profile Updation Failed at parents detail');
                    }
                } else {
                    $response = ApiResponseController::responseFailed('Student Profile Updation Failed at address');
                }
            } else {
                $response = ApiResponseController::responseFailed('Student Profile Updation Failed at student_profiles');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        } catch (\Exception $e) {
            $response = ApiResponseController::responseServerError($e->getMessage());
        }
        return $response;
    }
}
