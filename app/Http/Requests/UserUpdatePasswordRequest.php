<?php

namespace App\Http\Requests;

use Txsoura\Core\Http\Requests\CoreRequest;

class UserUpdatePasswordRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|different:current_password|confirmed',
        ];
    }
}
