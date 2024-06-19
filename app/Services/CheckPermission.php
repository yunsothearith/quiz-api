<?php

namespace App\Services;

use App\Enum\RoleEnum;
use App\Models\Permission\Permission;
use Tymon\JWTAuth\Facades\JWTAuth;

trait CheckPermission
{

    public function checkPermission($slug = "", $action = "")
    {
        $roleId = JWTAuth::user()->role_id;

        if ($roleId == RoleEnum::ADMIN || $roleId == RoleEnum::MANAGER || $roleId == RoleEnum::REGISTRATION || $roleId == RoleEnum::CONSULTANT || $roleId == RoleEnum::FINANCE) {

            $permision = Permission::with([
                'action',
                'part',
            ])
                ->where([
                    'role_id' => $roleId
                ])
                ->whereHas('action', function ($q) use ($action) {
                    $q->where('name', $action);
                })
                ->whereHas('part', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                })
                ->first();
            return [
                'check'    => $permision ? 1 : 0,
                'sys_role' => $permision,
            ];
        } else {
            return [
                'check'    => 1,
                'sys_role' => "Has role as " . JWTAuth::user()->role->name
            ];
        }
    }
}
