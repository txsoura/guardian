<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Txsoura\Core\Http\Requests\CoreRequest;

class UserUpdateCellphoneRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user = auth()->user();

        return [
            'cellphone' => ['required', 'numeric',
                Rule::unique('users', 'cellphone')
                    ->whereNull('deleted_at')],
            'code' => [
                Rule::requiredIf(($user->cellphone && $user->cellphone_verified_at)),
                'string', 'digits:6']
        ];
    }
}
