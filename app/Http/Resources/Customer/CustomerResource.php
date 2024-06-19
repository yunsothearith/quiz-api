<?php

namespace App\Http\Resources\Customer;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request)
    {
        return [
            "id"                 => $this->id,
            "name"               => $this->user->name ?? null,
            "phone"              => $this->user->phone ?? null,
            "email"              => $this->user->email ?? null,
            "avatar"             => $this->user->avatar ?? null,
            "gender"             => $this->user->gender ?? null,
            "card_no"            => $this->card_no ?? null,
            "dob"                => $this->dob ? Carbon::parse($this->dob)->format('Y-m-d H:i:s') :  null,
            "pob"                => $this->pob ?? null,
            "address"            => $this->address ?? null,
            "last_activity"      => $this->updated_at ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : null,
            "emergency_contact"  => $this->getEmergencyContact($this->emergencyContact),
            "total_amount"       => $this->transaction->amount ?? 0,
            "total_services"     => $this->transaction->services->count() ?? 0,
            "attachments"        => $this->getCustomerAttachments($this->attachments),
            "transaction"        => $this->getTransaction($this->transaction),
        ];
    }

    private function getCustomerAttachments($attachments)
    {
        $data = [];
        if ($attachments->count() == 0) return $data;
        foreach ($attachments as $attachment) {
            $data[] = [
                "id"      => $attachment->id,
                "name"    => $attachment->name ?? null,
                "type"    => $attachment->type ?? null,
                "uri"     => $attachment->uri ?? null,
                "creator" => $attachment->creator ?? null,
            ];
        }
        return $data;
    }

    private function getEmergencyContact($emergencyContact)
    {
        if ($emergencyContact) {
            return [
                "id"        => $emergencyContact->id ?? null,
                "name"      => $emergencyContact->name ?? null,
                "phone"     => $emergencyContact->phone ?? null,
                "address"   => $emergencyContact->address ?? null,
            ];
        }
        return null;
    }

    private function getTransaction($transaction)
    {
        if ($transaction) {
            return [
                "id"          => $transaction->id,
                "trx_no"      => $transaction->trx_no ?? null,
                "status"      => $transaction->status ?? null,
                "creator"     => $transaction->creator ?? null,
                "amount"      => $transaction->amount ?? 0,
                "attachments" => $this->getAttachments($transaction->attachments),
                "services"    => $this->getServices($transaction->services, $transaction->id),
            ];
        }
        return null;
    }

    private function getAttachments($attachments)
    {
        $data = [];
        if ($attachments->count() == 0) return $data;
        foreach ($attachments as $attachment) {
            $data[] = [
                "id"               => $attachment->id,
                "status"           => $attachment->status,
                "type"             => $attachment->type ?? null,
                "uri"              => $attachment->uri ?? null,
                "note"             => $attachment->note,
                "creator"          => $attachment->creator ?? null,
                "attachments_type" => $attachment->attachmentsType ?? null,
                "updated_at"       => $attachment->updated_at ?? null,
            ];
        }
        return $data;
    }

    private function getServices($services, $transactionId)
    {
        $data = [];
        if ($services->count() == 0) return $data;
        foreach ($services as $service) {
            $data[] = [
                "id"            => $service->id,
                "kh_name"       => $service->kh_name ?? null,
                "en_name"       => $service->en_name ?? null,
                "code"          => $service->code ?? null,
                "fee"           => $service->fee ?? null,
                "country"       => $service->country ?? null,
                "stutus"        => $service->status($transactionId, $service->id),
                "consultants"   => $this->getConsaltans($service->consultants)
            ];
        }
        return $data;
    }

    private function getConsaltans($consultants)
    {
        $data = [];
        if ($consultants->count() == 0) return $data;
        foreach ($consultants as $consultant) {
            $data[] = [
                "id"         => $consultant->id,
                "user_id"    => $consultant->user_id,
                "name"       => optional($consultant->user)->name,
                "phone"      => optional($consultant->user)->phone,
                "avatar"     => optional($consultant->user)->avatar,
                "created_at" => $consultant->created_at ?? null
            ];
        }
        return $data;
    }
}
