<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'avatar'       => $this->avatar,
            'gender'       => $this->gender,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'transactions' => $this->getTansactions($this->transactions),
            'modified_at'  => $this->updated_at,
        ];
    }

    private function getTansactions($transactions)
    {
        $data = [];
        if ($transactions->count() == 0) return $data;
        foreach ($transactions as $transaction) {
            $data[] = [
                "id"            => $transaction->id,
                "status"        => $transaction->status,
                "customer"      => $this->getCustomer($transaction->customer),
                "services"      => $this->getServices($transaction->services, $transaction)
            ];
        }
        return $data;
    }

    private function getCustomer($customer)
    {
        if ($customer) {
            return [
                "id"                 => $customer->id,
                "name"               => $customer->user->name ?? null,
                "phone"              => $customer->user->phone ?? null,
                "avatar"             => $customer->user->avatar ?? null,
                "card_no"            => $customer->card_no ?? null
            ];
        }
        return null;
    }

    private function getServices($services, $transaction)
    {
        $data = [];
        if ($services->count() == 0) return $data;
        foreach ($services as $service) {
            $data[] = [
                "id"                       => $service->id,
                "kh_name"                  => $service->kh_name ?? null,
                "en_name"                  => $service->en_name ?? null,
                "code"                     => $service->code ?? null,
                "fee"                      => $service->fee ?? null,
                "country"                  => $service->country ?? null,
                "stutus"                   => $service->status($transaction->id, $service->id)
            ];
        }
        return $data;
    }
}
