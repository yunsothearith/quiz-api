<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    private $paginations;
    public function __construct($resource)
    {
        $this->paginations = [
            'total'         => $resource->total(),
            'per_page'      => $resource->perPage(),
            'current_page'  => $resource->currentPage(),
            'total_pages'   => $resource->lastPage()
        ];
        $resource = $resource->getCollection();
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        $data = [];
        foreach ($this->collection as $row) {
            $data[] = [
                "id"                    => $row->id,
                "trx_no"                => $row->transaction->trx_no,
                "status"                => $row->transaction->status ?? null,
                "customer"              => $this->getCustomer($row),
                "services"              => $row->transaction->services->pluck('code')->toArray() ?? 0,
                "total_fee"             => $row->transaction->services->sum(function ($service) {
                                                return $service->fee->name ?? 0;
                                            }) ?? 0,
            ];
        };
        return [
            'data'          => $data,
            'pagination'    => $this->paginations
        ];
    }
    private function getCustomer($customer)
    {
        if ($customer) {
            return [
                'id'         => $customer->id,
                'name'       => $customer->user->name ?? null,
                'phone'      => $customer->user->phone ?? null,
                'avatar'     => $customer->user->avatar ?? null,
                'email'      => $customer->user->email ?? null,
                'dob'        => $customer->dob ?? null,
                'pob'        => $customer->pob ?? null,
                'address'    => $customer->address ?? null,
                'created_at' => $customer->created_at ?? null
            ];
        }
        return null;
    }
}
