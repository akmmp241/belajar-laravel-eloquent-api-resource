<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 * */
class ProductResource extends JsonResource
{
    public static $wrap = "value";

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "category" => new CategorySimpleResource($this->category),
            "price" => $this->price,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at,
        ];
    }
}