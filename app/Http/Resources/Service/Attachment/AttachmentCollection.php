<?php

namespace App\Http\Resources\Service\Attachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttachmentCollection extends ResourceCollection
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
