<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        return auth()->check() && $user->can('user_update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'surname' => 'sometimes|string',
            'email' => 'sometimes|string',
            'username' => 'sometimes|string',
            'password' => 'sometimes|confirmed|string',
            'role' => 'sometimes|string',
            'middle_name' => 'sometimes|string',
            'bio' => 'sometimes|string',
        ];
    }
}
