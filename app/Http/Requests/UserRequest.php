<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = backpack_auth()->user();
        $permissions = $user->getAllPermissions()->pluck('name');
    
        $requiredPermissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'view reports for posts',
            'create users',
            'edit users',
            'delete users',
            'view users',
            'assign roles',
            'create comments',
            'edit comments',
            'delete comments',
            'view comments',
            'view reports for comments',
        ];
    
        // Vérifier si l'utilisateur a toutes les permissions requises
        foreach ($requiredPermissions as $permission) {
            if (!$permissions->contains($permission)) {
                return false; // Retourne false si une permission est manquante
            }
        }
    
        return true; // Retourne true si l'utilisateur a toutes les permissions
    }

       
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name' => 'required|string|min:3|max:255',
            'email' => [
                'required',
            ],
            'password' => $this->isMethod('post') ? 'required|string|min:8' : 'nullable|string|min:8',

        ];

        // // Si l'utilisateur est en train de créer un nouvel utilisateur, le mot de passe est obligatoire
        // if ($this->isMethod('post')) {
        //     $rules['password'] = 'required|string|min:8|confirmed';
        // }

        // // Si l'utilisateur est en train de mettre à jour un utilisateur existant, le mot de passe est optionnel
        // if ($this->isMethod('put') || $this->isMethod('patch')) {
        //     $rules['password'] = 'nullable|string|min:8|confirmed';
        // }

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'nom',
            'email' => 'adresse e-mail',
            'password' => 'mot de passe',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.min' => 'Le nom doit comporter au moins 3 caractères.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire pour la création.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}
