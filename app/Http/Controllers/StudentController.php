<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User_profile;
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
        //return auth()->user()->id;

        try {

            $userID = auth()->user()->id;
            $user = User_profile::select('user_id')
                ->where('user_id', auth()->user()->id)
                ->get();
            // return $user;
            if (!$user) {
                return  [
                    'success'   => false,
                    'message'   => 'Profile Exist',
                    'data'      =>
                    'User profile already exists for User : ' . $userID,
                    'Status'    => '203'
                ];
            }

            $result = User_profile::insert([
                'user_id' => $userID,
                'profile_picture' => $request->profile_picture,
                'role' => $request->role,
                'current_school' => $request->current_school,
                'previous_school' => $request->previous_school,
                'teacher_experience' => 0,
                'is_approved' => 0,
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
                        ];
                    }
                } else {
                    $response = [
                        'result' => 'User Creation Failed at address',
                    ];
                }
            } else {
                $response = [
                    'result' => 'User Creation Failed at user_profiles',
                    203,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function rules()
    // {
    //     return [
    //         'profile_picture' => 'required',
    //         'current_school' => 'required',
    //         'previous_school' => 'required',
    //         'address_1' => 'required',
    //         'address_2' => 'required',
    //         'city' => 'required',
    //         'state' => 'required',
    //         'country' => 'required',
    //         'pin_code' => 'required',
    //         'father_name' => 'required',
    //         'mother_name' => 'required',
    //         'father_occupation' => 'required',
    //         'mother_occupation' => 'required',
    //         'father_contact_no' => 'required',
    //         'mother_contact_no' => 'required',
    //     ];
    // }
    // public function messages()
    // {
    //     return [
    //         'profile_picture.reuired' => 'Profile picture is required',
    //         'current_school.required' => 'Current School is required',
    //         'previous_school.required' => 'Previous School is required',
    //         'address_1.required' => 'Address 1 field is required',
    //         'address_2.required' => 'Address 2 field is required',
    //         'city.required' => 'City is required',
    //         'state.required' => 'State is required',
    //         'country.required' => 'Country is required',
    //         'pin_code.required' => 'Pin Code is required',
    //         'father_name.required' => 'Father name is required',
    //         'mother_name.required' => 'Mother Name is required',
    //         'father_occupation.required' => 'Father occupation required',
    //         'mother_occupation.required' => 'Mother occupation required',
    //         'father_contact_no.required' => 'Father contact required',
    //         'mother_contact_no.required' => 'Mother contact required',
    //     ];
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $users = User_profile::join('users', 'id', '=', 'user_profiles.user_id')
                ->join(
                    'addresses',
                    'addresses.user_id',
                    '=',
                    'user_profiles.user_id'
                )
                ->join(
                    'parents_details',
                    'parents_details.user_id',
                    '=',
                    'user_profiles.user_id'
                )
                ->select(
                    'users.name',
                    'user_profiles.*',
                    'addresses.*',
                    'parents_details.*'
                )
                ->where('role', '=', 'Student')
                ->where('user_profiles.user_id', '=', $id)
                ->get();
            if (count($users) > 0) {
                return [
                    'status' => '200',
                    'record found' => count($users),
                    $users,
                ];
            } else {
                return [
                    'status' => '203',
                    'record found' => count($users),
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return ['Error : ' . $e];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $rules = [
                'profile_picture' => 'required',
                'current_school' => 'required',
                'previous_school' => 'required',
                'address_1' => 'required',
                'address_2' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pin_code' => 'required',
                'father_name' => 'required',
                'mother_name' => 'required',
                'father_occupation' => 'required',
                'mother_occupation' => 'required',
                'father_contact_no' => 'required',
                'mother_contact_no' => 'required',
            ];
            if (!$request == null) {
                $validated = Validator::make($request->all(), $rules);
                if ($validated->fails()) {
                    return $validated->errors();
                } else {
                    User::where('id', $request->user_id)
                        ->update([
                            'name' => $request->name,
                        ]);

                    $result = User_profile::where('user_id', $request->user_id)
                        ->update([
                            'profile_picture' => $request->profile_picture,
                            'current_school' => $request->current_school,
                            'previous_school' => $request->previous_school,
                        ]);

                    $result1 = Address::where('user_id', $request->user_id)
                        ->update([
                            'address_1' => $request->address_1,
                            'address_2' => $request->address_2,
                            'city' => $request->city,
                            'state' => $request->state,
                            'country' => $request->country,
                            'pin_code' => $request->pin_code,
                        ]);

                    $result2 = Parents_detail::where('user_id', $request->user_id)
                        ->update([
                            'father_name' => $request->father_name,
                            'mother_name' => $request->mother_name,
                            'father_occupation' => $request->father_occupation,
                            'mother_occupation' => $request->mother_occupation,
                            'father_contact_no' => $request->father_contact_no,
                            'mother_contact_no' => $request->mother_contact_no,
                        ]);
                    return [
                        'result' => ' Data updated Successfully',
                        'status' => '200',
                    ];
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            //throw $th;
            return ['Error : ' . $e];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
