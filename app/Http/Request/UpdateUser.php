<?php

namespace Inggo\Boilerplate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Inggo\Boilerplate\User;

use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = User::find($this->route('user'));

        return $user && $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $email_rules = [
            'email',
            Rule::unique('users')->ignore($this->route('user'))
        ];

        if (env('ADMIN_REQUIRE_USERNAME', true)) {
            $email_rules[] = 'required';
        }

        $username_rules = [
            'alpha_dash',
            Rule::unique('users')->ignore($this->route('user'))
        ];

        if (env('ADMIN_REQUIRE_USERNAME', true)) {
            $username_rules[] = 'required';
        }

        return [
            'email' => $email_rules,
            'username' => $username_rules,
            'name' => 'required|min:4',
            'role' => 'in:' . implode(',', $this->user()->allowed_roles_to_assign),
            'storages' => 'array|exists:storages,id'
        ];
    }
}
