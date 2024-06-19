<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        $data = [];
        foreach ($this->collection as $row) {
            $data[] = [
                "id"        => $row->id,
                "abbre"     => $row->abbre ?? null,
                "services"  => $this->getServices($row->services, $row->id),
            ];
        };
        return $data;
    }

    private function getServices($services, $country_id)
    {
        $data = [];
        if ($services->count() == 0) return $data;
        foreach ($services as $service) {
            if ($service->country_id == $country_id) {
                $data[] = [
                    "id"          => $service->id,
                    "kh_name"     => $service->kh_name ?? null,
                    "en_name"     => $service->en_name ?? null,
                    "code"        => $service->code ?? null,
                    "fee"         => $service->fee ?? null,
                    "type"        => $service->type ?? null,
                    "attachments" => $this->getAttachments($service->attachments),
                ];
            }
        }
        return $data;
    }

    private function getAttachments($attachments)
    {
        $data = [];
        if ($attachments->count() == 0) return $data;
        foreach ($attachments as $attachment) {
            $data[] = [
                "id"     => $attachment->id,
                "type"   => $attachment->type ?? null,
            ];
        }
        return $data;
    }
}
