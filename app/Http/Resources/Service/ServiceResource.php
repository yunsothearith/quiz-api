<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            "kh_name"            => $this->kh_name ?? null,
            "en_name"            => $this->en_name ?? null,
            "code"               => $this->code ?? null,
            "fee"                => $this->fee ?? null,
            "type"               => $this->type ?? null,
            "country"            => $this->country ?? null,
            "attachments"        => $this->getAttachments($this->attachments),
            "consultants_count"  => $this->consultants->count() ?? 0,
            "transactions_count" => $this->transactions->count() ?? 0,
            "invoices_count"     => $this->invoices->count() ?? 0,
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
