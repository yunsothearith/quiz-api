<?php

namespace App\Http\Resources\Transaction;

use App\Enum\RoleEnum;
use App\Models\Consultant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Tymon\JWTAuth\Facades\JWTAuth;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            "id"            => $this->id,
            "status"        => $this->status,
            "customer"      => $this->getCustomer($this->customer),
            "services"      => $this->getServices($this->services, $this),
            "payments"      => $this->getInvoices($this->invoices)
        ];
    }

    private function getCustomer($customer)
    {
        if ($customer) {
            return [
                "id"                 => $customer->id,
                "name"               => $customer->user->name ?? null,
                "phone"              => $customer->user->phone ?? null,
                "email"              => $customer->user->email ?? null,
                "avatar"             => $customer->user->avatar ?? null,
                "gender"             => $customer->user->gender ?? null,
                "card_no"            => $customer->card_no ?? null,
                "dob"                => $customer->dob ? Carbon::parse($customer->dob)->format('Y-m-d H:i:s') :  null,
                "pob"                => $customer->pob ?? null,
                "address"            => $customer->address ?? null,
                "emergency_contact"  => $this->getEmergencyContact($customer->emergencyContact),
                "attachments"        => $this->getCustomerAttachments($customer->attachments)
            ];
        }
        return null;
    }

    private function getInvoices($invoices)
    {
        $data = [];
        if ($invoices->count() == 0) return $data;
        foreach ($invoices as $invoice) {
            $data[] = [
                "id"         => $invoice->id,
                "invoice_no" => $invoice->invoice_no,
                "service"    => $invoice->service ? [
                    'id'    => $invoice->service->id,
                    'code'    => $invoice->service->code,
                    'fee'     => $invoice->service->fee,
                    'kh_name' => $invoice->service->kh_name,
                    'en_name' => $invoice->service->en_name,
                ] : null,
                "status"     => $invoice->status ?? null,
                "type"       => $invoice->type ?? null,
                "uri"        => $invoice->uri ?? null,
                "updated_at" => $invoice->updated_at ?? null
            ];
        }
        return $data;
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
                "status"                   => $service->status($transaction->id, $service->id),
                "payment_status"           => $service->paymentStatus($transaction->id, $service->id),
                "consultants"              => $this->getConsaltans($service->consultants, $transaction->id),
                "transaction_attachments"  => $this->getTransactionAttachments($service->id, $transaction->attachments),
                "created_at"               => $service->created_at,
                "access"                   => $this->check($transaction->id, $service->id),
            ];
        }
        return $data;
    }

    private function check($transactionId,$serviceId)
    {
        $data = [];

        $can_read   = 1;
        $can_create = 0;
        $can_update = 0;
        $can_delete = 0;

        $userId = JWTAuth::user()->id;
        

        $consultant = Consultant::where([
            'user_id' => $userId,
            'transaction_id' => $transactionId,
            'service_id' => $serviceId
        ])->first();

        if ($consultant || JWTAuth::user()->role_id == RoleEnum::MANAGER) {
            $can_create = 1;
            $can_update = 1;
            $can_delete = 1;
        }
        $data = (object)[
            "can_read"   => $can_read,
            "can_create" => $can_create,
            "can_update" => $can_update,
            "can_delete" => $can_delete
        ];
        return $data;
    }

    private function getConsaltans($consultants, $transactionId)
    {
        $data = [];
        if ($consultants->count() == 0) return $data;
        foreach ($consultants as $consultant) {
            if ($consultant->transaction_id == $transactionId) {
                $data[] = [
                    "id"         => $consultant->id,
                    "user_id"    => $consultant->user_id,
                    "name"       => optional($consultant->user)->name,
                    "phone"      => optional($consultant->user)->phone,
                    "avatar"     => optional($consultant->user)->avatar,
                    "created_at" => $consultant->created_at ?? null,
                    "adder"      => optional($consultant->adder)->name,
                ];
            }
        }
        return $data;
    }

    private function getTransactionAttachments($service_id, $attachments)
    {
        $data = [];
        if ($attachments->count() == 0) return $data;
        foreach ($attachments as $attachment) {
            if ($attachment->service_id == $service_id) {
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
        }
        return $data;
    }
}
