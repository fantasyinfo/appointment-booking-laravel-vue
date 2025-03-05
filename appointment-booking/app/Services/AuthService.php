<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    protected $authRepo;

    // create auth repo object
    public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    // register
    public function register($userData)
    {
        try {

            // first check if the same email is not already exists

            if ($this->authRepo->checkAlreadyExists($userData['email'])) {
                return ['error' => true, 'message' => 'This email address already exists, please login.'];
            }

            // hash the password

            $userData['password'] = Hash::make($userData['password']);

            // register the user now
            return $this->authRepo->create($userData);

        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }




    }

    // login
    public function login($userData)
    {
        try {
            // check if email is exists into db
            // Check if the user exists
            $user = $this->authRepo->findByEmail($userData['email']);

            if (!$user || !Hash::check($userData['password'], $user->password)) {
                return ['error' => true, 'message' => 'Invalid credentials'];
            }

            // Revoke old tokens (if needed)
            $user->tokens()->delete();

            // Create new Sanctum token
            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'error' => false,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user
            ];

        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    // logout
    public function logout($user)
    {
        try {
            $user->currentAccessToken()->delete();
            return ['error' => false, 'message' => 'Logout successful'];

        } catch (Exception $e) {
            \Log::error('something went wrong', [$e->getMessage()]);
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

}