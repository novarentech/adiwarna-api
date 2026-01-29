<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTransmittalResource extends JsonResource
{
    /**
     * Indicates if the resource is being used for list view.
     */
    public static $listView = false;

    /**
     * Create a new resource instance for list view.
     */
    public static function collection($resource)
    {
        static::$listView = true;
        return parent::collection($resource);
    }

    public function toArray(Request $request): array
    {
        // Simplified response for list view
        if (static::$listView) {
            return [
                'id' => $this->id,
                'ta_no' => $this->ta_no,
                'date' => $this->date->format('Y-m-d'),
                'customer' => $this->customer?->name,
                'pic' => $this->pic_name,
            ];
        }

        // Full response for detail view
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ta_no' => $this->ta_no,
            'date' => $this->date->format('Y-m-d'),
            'customer' => [
                'id' => $this->customer_id,
                'name' => $this->customer?->name,
                'address' => $this->customer?->address,
                'district' => $this->customer_district,
            ],
            'pic_name' => $this->pic_name,
            'report_type' => $this->report_type,
            'documents' => $this->whenLoaded('documents', function () {
                return $this->documents->map(function ($document) {
                    return [
                        'id' => $document->id,
                        'wo_number' => $document->wo_number,
                        'wo_year' => $document->wo_year,
                        'location' => $document->location,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
