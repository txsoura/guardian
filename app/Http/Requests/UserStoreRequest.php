<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Txsoura\Core\Http\Requests\CoreRequest;

class UserStoreRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role_id' => 'required|numeric|exists:acl_roles,id',
            'password' => 'required|string',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        return $this->merge([
            'email' => Str::lower($this->email),
            'password' => Str::random(8),
        ]);
    }
}
