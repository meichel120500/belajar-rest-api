<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registration(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
             'name' => 'required|string',
             'email' => 'required|email|unique:users,email',
             'password' => 'required|min:6|confirmed',
            ]); 
     
            if($validator->fails()) {
             return response()->json([
                 'status' => false,
                 'message' => 'validation error',
                 'error' => $validator->errors()
             ], 422);
            }
            //query insert
            $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => $request->password,
            ]);
     
            return response()->json([
             'status' => true,
             'message' => 'Registration Success',
             'data' => $user,
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $th->getMessage()//tidak boleh dimunculkan di prod
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
             'email' => 'required|email',
             'password' => 'required|min:6',
            ]); 
     
            if($validator->fails()) {
             return response()->json([
                 'status' => false,
                 'message' => 'validation error',
                 'error' => $validator->errors()
             ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                 'status' => false,
                 'message' => 'Invalid credential!',
             ], 401);
            }

            $token= $user->createToken('auth_token')->plainTextToken;
            return response()->json([
             'status' => true,
             'message' => 'Login Success',
             'data' => $user,
             'token' => $token,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $th->getMessage()//tidak boleh dimunculkan di prod
            ], 500);
        }
    }
}