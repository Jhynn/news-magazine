<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
	public function index(): array
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

        return $tokens->toArray();
    }

    public function store(array $properties)
    {
        $user = User::where('email', $properties['email_or_username'])
            ->orWhere('username', $properties['email_or_username'])
            ->first();

        if (!$user || !Hash::check($properties['password'], $user->password))
            throw new \Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);

        if ($user->status === 'inactive')
            throw new \Exception('this user is inactive', Response::HTTP_FORBIDDEN);

        $token = explode(
            '|', $user->createToken($properties['token_name'] ?? '')
            ->plainTextToken
        );

        return [
            'name' => $properties['token_name'] ?? "token_{$token[0]}",
            'token' => $token[1],
            // 'role' => $user->getRoleNames()->first(),
            // 'permissions' => $user->getAllPermissionsAPI(),
        ];
    }

    public function destroy(Request $request)
    {
        $token = (new PersonalAccessToken())
            ->findToken(str_replace('Bearer ', '', $request->header('authorization')));
        $token->delete();

        return $token;
    }
}