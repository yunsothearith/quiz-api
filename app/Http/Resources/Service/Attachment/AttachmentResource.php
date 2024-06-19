<?php

namespace App\Http\Resources\Service\Attachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"                 => $this->id,
            "attachments"        => $this->getAttachments($this->attachments),
        ];
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
