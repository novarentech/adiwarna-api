<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'po_no' => $this->po_no,
            'po_year' => $this->po_year,
            'date' => $this->date->format('Y-m-d'),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'pic_name' => $this->pic_name,
            'pic_phone' => $this->pic_phone,
            'required_date' => $this->required_date,
            'top_dp' => $this->top_dp,
            'top_cod' => $this->top_cod,
            'quotation_ref' => $this->quotation_ref,
            'purchase_requisition_no' => $this->purchase_requisition_no,
            'purchase_requisition_year' => $this->purchase_requisition_year,
            'discount' => $this->discount,
            'req_name' => $this->req_name,
            'req_pos' => $this->req_pos,
            'app_name' => $this->app_name,
            'app_pos' => $this->app_pos,
            'auth_name' => $this->auth_name,
            'auth_pos' => $this->auth_pos,
            'items' => PurchaseOrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
