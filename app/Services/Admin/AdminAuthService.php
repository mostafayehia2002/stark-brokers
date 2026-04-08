<?php

namespace App\Services\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Requests\LoginAdminRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    /**
     * Create a new class instance.
     */

    public function login(LoginAdminRequest $request): array
    {
        try {
            $credentials = $request->validated();
            $remember = $request->has('remember');
            $user = User::where('type', UserType::ADMIN)->where('status', UserStatus::ACTIVE)
                ->where(function ($query) use ($credentials) {
                    $query->where('email', $credentials['emailOrPhone'])
                        ->orWhere('phone', $credentials['emailOrPhone']);
                })
                ->first();
            if ($user && Hash::check(request()->input('password'), $user->password)) {
                Auth::login($user, $remember);
                return [
                    'success' => true,
                    'message' =>translate_message('success_login') ,
                ];
            }
            return [
                'success' => false,
                'message' =>translate_message('invalid_credentials'),
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


    public function logout(): array
    {
        try {
            if (Auth::check()) {

                Auth::logout();
                return [
                    'success' => true,
                    'message' => translate_message('success_logout'),
                ];
            }
            return [
                'success' => false,
                'message' => translate_message('unauthenticated'),
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


}
