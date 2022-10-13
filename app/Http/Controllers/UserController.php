<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\Register;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use Exception;

class UserController extends Controller
{
    public function register(RegisterRequest $req)
    {
        try {
            //return $req->all();
            $input = $req->all();
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            $responseArray = [
                'Id' => $user->id,
                'name' => $user->name,
                'user_type' => $user->user_type,
                'email' => $user->email,
                'token' => $user->createToken('register-token')->accessToken,
                'message' => 'User Created Successfully',
            ];
            return response()->json($responseArray, 200);
        } catch (\Exception $ex) {

            return response()->json($ex->getMessage(),);
        }
    }

    public function login(LoginRequest $req)
    {
        try {
            if (
                !Auth::attempt([
                    'email' => $req->email,
                    'password' => $req->password,
                ])
            ) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Authontication errors',
                    'data'      => 'Username or Password incorrect',
                    'Status'    => '422'
                ]);
            }
            $responseArray = [
                'Id' => auth()->user()->id,
                'user_type' => auth()->user()->user_type,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'token' => auth()
                    ->user()
                    ->createToken('login-token')->accessToken,
                'success'   => true,
                'message' => 'Successfull',
                'data'      => 'User Logged in Successfully',
                'Status'    => '200'
            ];
            return response()->json($responseArray, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 203);
        }
    }
    public function logout(Request $request)
    {
        try {
            auth()->user()->token()->revoke();

            return response()->json(
                [
                    'success'   => true,
                    'message'   => 'Success',
                    'data'      => 'User Logged out Successfully',
                    'Status'    => '203'
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                'success'   => false,
                'message'   => 'Logout Failed',
                'data'      => $th->getMessage(),
                'Status'    => '203'
            ]);
        }
    }
}
