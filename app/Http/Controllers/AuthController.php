<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'unique:users,email'],
      'password' => ['required', 'string', 'min:8'],
    ]);

    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);


    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
      'message' => 'User registered',
      'user' => $user,
      'token' => $token,
    ], 201);
  }

  public function login(Request $request)
  {
    $data = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required', 'string'],
    ]);

    $user = User::where('email', $data['email'])->first();

    if (!$user || !Hash::check($data['password'], $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials are incorrect.'],
      ]);
    }




    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
      'message' => 'Logged in',
      'user' => $user,
      'token' => $token,
    ], 200);
  }

  public function me(Request $request)
  {
    return response()->json($request->user());
  }

  public function logout(Request $request)
  {

    $request->user()->currentAccessToken()->delete();




    return response()->json(['message' => 'Logged out']);
  }
}
