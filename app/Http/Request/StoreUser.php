<?php

namespace Inggo\Boilerplate\Http\Requests;

use Inggo\Boilerplate\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => env('ADMIN_REQUIRE_USERNAME', true)
                ? 'required|alpha_dash|unique:users'
                : 'alpha_dash|unique:users',
            'email' => env('ADMIN_REQUIRE_USERNAME', true)
                ? 'required|email|unique:users'
                : 'email|unique:users',
            'name' => 'required|min:4',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:' . implode(',', $this->user()->allowed_roles_to_assign),
            'storages' => 'array|exists:storages,id'
        ];
    }
}
