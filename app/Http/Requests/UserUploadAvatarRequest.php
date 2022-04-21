<?php

namespace App\Http\Requests;

use Txsoura\Core\Http\Requests\CoreRequest;

class UserUploadAvatarRequest extends CoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'avatar' => 'required|max:2000|image',
        ];
    }
}
