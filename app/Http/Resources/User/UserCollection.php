<?php

namespace App\Http\Resources\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dataFormat = [];
        foreach ($this->collection as $row) {
            $dataFormat[strtolower($row->name)] = count($row->users) ? $row->users->map(function ($user) {
                return [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'avatar'       => $user->avatar,
                    'gender'       => $user->gender,
                    'email'        => $user->email,
                    'phone'        => $user->phone,
                    'is_active'    => $user->is_active,
                    'modified_at'  => Carbon::parse($user->updated_at)->format('Y-m-d H:i:s'),
                ];
            }) : [];
        }
        return $dataFormat;
    }
}
