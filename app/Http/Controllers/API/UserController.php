<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'country' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed']
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->rol = 0;
        $user->save();

        return response()->json([
            'status' => 1,
            'message' => 'Register successful'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!isset($user->id)) {
            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid',
                'errors' =>  (object)['email' => ['The email doesnt exist']]
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid',
                'errors' =>  (object)['password' => ['The password doesnt match']]
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 1,
            'message' => 'Login Successful',
            'data' => (object)['user_token' => $token]
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 1,
            'message' => 'logout successful',
        ]);
    }

    public function userProfile()
    {
        return response()->json([
            'status' => 1,
            'message' => 'user data',
            'data' => auth()->user(),
        ]);
    }

    public function editUser(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'country' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed']
        ]);


        $user = User::find(auth()->user()->id);

        if ($user->email != $request->email) {
            $request->validate([
                'email' => ['unique:users'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 0,
                'message' => 'The given data was invalid',
                'errors' =>  (object)['password' => ['The password doesnt match']]
            ], 404);
        }

        foreach ($request->except('_token') as $key => $part) {
            if ($key == "password_confirmation" || $key == "password") continue;
            if ($request[$key] != $user[$key]) $user[$key] = $request[$key];
        }
        $user->save();

        return response()->json([
            'status' => 1,
            'data' => (object)['user' => (object)['data' => $user]],
        ]);
    }
}
