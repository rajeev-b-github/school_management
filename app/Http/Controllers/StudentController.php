<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student_profile;
use App\Models\Address;
use App\Models\User;
use App\Models\Parents_detail;


class StudentController extends Controller
{
    public function get_id()
    {
        return auth()->user()->id;
    }
    public function store(StudentRequest $request)
    {
        try {

            $userID = auth()->user()->id;
            $response[] = "";
            $result = Student_profile::insert([
                'user_id' => $userID,
                'profile_picture' => $request->profile_picture,
                'current_school' => $request->current_school,
                'previous_school' => $request->previous_school,
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
                    $result2 = Parents_detail::insert([
                        'user_id' => $userID,
                        'father_name' => $request->father_name,
                        'mother_name' => $request->mother_name,
                        'father_occupation' =>
                        $request->father_occupation,
                        'mother_occupation' =>
                        $request->mother_occupation,
                        'father_contact_no' =>
                        $request->father_contact_no,
                        'mother_contact_no' =>
                        $request->mother_contact_no,
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
                            'User Creation Failed at parents detail',
                            'status' => '203',
                            'UserId' => $userID,
                        ];
                    }
                } else {
                    $response = [
                        'result' => 'User Creation Failed at address',
                        'status' => '200',
                        'UserId' => $userID,
                    ];
                }
            } else {
                $response = [
                    'result' => 'User Creation Failed at student_profiles',
                    'status' => '203',
                    'UserId' => $userID,
                ];
            }
            return  $response;
        } catch (\Illuminate\Database\QueryException $e) {
            return [
                'result' => $e,
                203,
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
            $response[] = "";
            $userID = auth()->user()->id;
            $users = Student_profile::join('users', 'id', '=', 'student_profiles.user_id')
                ->join(
                    'addresses',
                    'addresses.user_id',
                    '=',
                    'student_profiles.user_id'
                )
                ->join(
                    'parents_details',
                    'parents_details.user_id',
                    '=',
                    'student_profiles.user_id'
                )
                ->select(
                    'users.name',
                    'student_profiles.*',
                    'addresses.*',
                    'parents_details.*'
                )
                ->where('users.user_type', '=', 'Student')
                ->where('student_profiles.user_id', '=', $userID)
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
    public function update(StudentRequest $request)
    {
        try {

            $response[] = "";
            $userID = auth()->user()->id;
            User::where('id', $userID)
                ->update([
                    'name' => $request->name,
                ]);

            Student_profile::where('user_id', $userID)
                ->update([
                    'profile_picture' => $request->profile_picture,
                    'current_school' => $request->current_school,
                    'previous_school' => $request->previous_school,
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

            Parents_detail::where('user_id', $request->user_id)
                ->update([
                    'father_name' => $request->father_name,
                    'mother_name' => $request->mother_name,
                    'father_occupation' => $request->father_occupation,
                    'mother_occupation' => $request->mother_occupation,
                    'father_contact_no' => $request->father_contact_no,
                    'mother_contact_no' => $request->mother_contact_no,
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
}
