<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'content' => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Le contenu du commentaire est requis.',
            'content.string' => 'Le contenu du commentaire doit être une chaîne de caractères.',
            'content.max' => 'Le contenu du commentaire ne doit pas dépasser 1000 caractères.',
        ];
    }
}
