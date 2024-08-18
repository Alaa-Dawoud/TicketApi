<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $request->validated();

        $user = User::where('email', request('email'))->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return [
            'token' => $user->createToken(time())->plainTextToken
        ];
    }

    public function register(RegisterRequest $request){
        $data = $request->validated();
        User::create($data);
        return response(null, 204);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response(null, 204);
    }
}
