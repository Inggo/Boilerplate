<?php

namespace Inggo\Boilerplate\Http\Requests;

use Inggo\Boilerplate\User;
use Illuminate\Foundation\Http\FormRequest;
use Inggo\Boilerplate\Rules\MatchesOldPassword;

class ChangePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->id ? User::find($this->id) : $this->user();

        return $user && $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'password' => 'required|confirmed|min:6'
        ];

        if (!$this->id || $this->user()->id == $this->id) {
            $rules['current_password'] = [
                'required',
                new MatchesOldPassword
            ];
        }

        return $rules;
    }
}
