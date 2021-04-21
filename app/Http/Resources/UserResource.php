<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar(),
            'email' => $this->email,
            'cellphone' => $this->cellphone,
            'status' => $this->status,
            'role' => $this->role ? $this->role->name : '',
            'email_verified_at' => $this->email_verified_at,
            'cellphone_verified_at' => $this->cellphone_verified_at,
            'two_factor_enabled' => $this->two_factor_provider ? true : false,
            'permissions' => RolePermissionResource::collection($this->whenLoaded('permissions')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }

    private function avatar()
    {
        if (!empty($this->avatar)) {
            //Check if avatar is from provider
            if (str_contains($this->avatar, 'https')) {
                return $this->avatar;
            }

            return config('app.url') . Storage::url($this->avatar);
        }
    }
}
