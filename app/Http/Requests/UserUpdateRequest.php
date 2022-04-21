<?php

namespace App\Http\Requests;

use App\Enums\UserLang;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Txsoura\Core\Http\Requests\CoreRequest;

class UserUpdateRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'role_id' => 'sometimes|numeric|exists:acl_roles,id',
            'name' => 'sometimes|required|string',
            'lang' => ['sometimes', 'required', 'string', Rule::in(UserLang::toArray())],
            'fcm_token' => 'sometimes|nullable|string',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->role_id && auth()->user()->role != "admin") {
            throw new AccessDeniedHttpException;
        }

        if ($this->name) {
            $this->merge([
                'name' => ucwords($this->name)
            ]);
        }
    }
}
