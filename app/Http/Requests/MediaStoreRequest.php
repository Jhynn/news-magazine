<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        return auth()->check() && $user->can('media_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'media' => 'required|file',
            'mime_type' => 'required|string',
            'owner_id' => 'required|integer',
            'owner_type' => 'required|string',
            'metadata' => 'sometimes|json',
        ];
    }
}
