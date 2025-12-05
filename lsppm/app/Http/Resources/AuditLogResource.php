<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id ?? null,
            'user_name' => $this->user->name ?? null,
            'user_email' => $this->user->email ?? null,
            'user_role' => $this->user->role ?? null,
            'action' => $this->action,
            'target' => $this->target,
            'status' => $this->status,
            'ip_address' => $this->ip_address,
            'details' => $this->details,
            'created_at' => $this->created_at,
        ];
    }
}