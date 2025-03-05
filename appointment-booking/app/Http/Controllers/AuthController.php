<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    //
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function registerUser(RegisterRequest $registerRequest)
    {
        // register 
        $response = $this->authService->register($registerRequest->validated());

        if (!empty($response['error'])) {
            return response()->json([
                "error" => true,
                "message" => $response['message']
            ], 400);
        }
        return response()->json([
            "error" => false,
            "message" => "User Register Successfully.",
            "data" => $response

        ], 201);
    }

    public function loginUser(LoginRequest $loginRequest)
    {
        // login 
        $response = $this->authService->login($loginRequest->validated());

        if (!empty($response['error'])) {
            return response()->json([
                "error" => true,
                "data" => [],
                "message" => $response["message"]
            ]);
        }

        return response()->json([
            "error" => false,
            "message" => "User Login Successfully.",
            "token" => $response['token'],
            "user" => $response['user']

        ], 200);
    }

    public function logoutUser(Request $request)
    {
        $response = $this->authService->logout($request['user']);

        if (!empty($response['error'])) {
            return response()->json([
                "error" => true,
                "message" => $response['message']
            ], 400);
        }

        return response()->json([
            "error" => false,
            "message" => "Logout successful"
        ]);
    }
}

