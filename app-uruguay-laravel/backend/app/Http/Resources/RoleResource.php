<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        Carbon::setLocale('es');
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'is_protected' => $this->is_protected,
            'permissions'  => $this->whenLoaded('permissions', function () {
                return $this->permissions->pluck('name')->toArray();
            }),
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->translatedFormat('d \d\e F \d\e Y H:i') : null,
            'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->translatedFormat('d \d\e F \d\e Y H:i') : null,
        ];
    }
}
