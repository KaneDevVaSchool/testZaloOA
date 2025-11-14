<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'status' => (bool) $this->status,
            'number' => $this->number,
            'qr_token' => $this->qr_token,
            'code_staft' => $this->code_staft,
            'address' => $this->address,
            'invited_at' => $this->invited_at,
            'arrived_at' => $this->arrived_at,

            // load quan hệ
            'department' => new DepartmentResource($this->whenLoaded('department')),
        ];
    }
}
