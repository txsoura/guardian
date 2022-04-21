<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Txsoura\Core\Http\Requests\CoreRequest;

class UserUpdateEmailRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at')],
            'code' => 'required|string|digits:6'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'email' => Str::lower($this->email),
        ]);
    }
}
