<?php

namespace Modules\RegionBuilder\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class Regions extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request = null)
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
