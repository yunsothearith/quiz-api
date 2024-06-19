<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'note' => $this->note,
            'children' => PermissionResource::collection($this->whenLoaded('children')),
            'roles' => $this->getRoles($this->roles)
        ];
    }

    private function getRoles($roles)
    {
        $data = [];
        if ($roles->count() == 0) return $data;
        foreach ($roles as $role) {
            $data[] = [
                "id"        => $role->id,
                "name"      => $role->name,
                "access"    => $role->pivot ? [
                    'can_read'    => $role->pivot->can_read,
                    'can_create'  => $role->pivot->can_create,
                    'can_update'  => $role->pivot->can_update,
                    'can_delete'  => $role->pivot->can_delete
                ] : null
            ];
        }
        return $data;
    }
}