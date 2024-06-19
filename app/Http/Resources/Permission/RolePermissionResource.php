<?php

namespace App\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class RolePermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getPermission($this->permissions);
    }

    private function getPermission($permissions)
    {
        $data = [];
        if ($permissions->count() == 0) return $data;
        foreach ($permissions as $permission) {
            $data[] = [
                "id"        => $permission->id,
                "name"      => $permission->name,
                "section"   => $permission->section,
                "access"    => $permission->pivot ? [
                    'can_read'    => $permission->pivot->can_read,
                    'can_create'  => $permission->pivot->can_create,
                    'can_update'  => $permission->pivot->can_update,
                    'can_delete'  => $permission->pivot->can_delete
                ] : null
            ];
        }
        return $data;
    }
}
