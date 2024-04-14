<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
	public function index()
    {
        $tokens = auth()->user()->tokens()
                ->get()->map(function ($item) {

                    $aux = collect($item)
                        ->only(['name', 'token', 'created_at'])
                        ->all();
                    $token = $item['token'];
                    $aux['token'] = $token;

                    return $aux;
                });

        return $tokens;
    }

    public function store(array $properties)
    {
        if (Auth::attempt(['email' => $properties['email_or_username'], 'password' => $properties['password']]) ||
                Auth::attempt(['username' => $properties['email_or_username'], 'password' => $properties['password']])
            ) {
                $user = User::where('email', $properties['email_or_username'])
                    ->orWhere('username', $properties['email_or_username'])
                    ->first();

                if ($user->status === 'inactive')
                    throw new \Exception('this user is inactive', Response::HTTP_FORBIDDEN);

                $token = explode('|', auth()->user()->createToken($properties['token_name'] ?? 'login-frontend')->plainTextToken);

                return response()->json([
                    'data' => [
                        'name' => $properties['token_name'] ?? 'login-frontend',
                        'token' => $token[1],
                        'role' => $user->getRoleNames()->first(),
                        'permissions' => $user->getAllPermissionsAPI(),
                    ],
                ]);
            }

        throw new \Exception(__('auth.failed'));
    }

    public function destroy(Request $request)
    {
        $token = (new PersonalAccessToken())
            ->findToken(str_replace('Bearer ', '', $request->header('authorization')));
        $token->delete();

        return $token;
    }
}