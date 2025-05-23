<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "product_id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "image" => asset("storage" . "/" . $this->image),
            "quantity" => $this->quantity,
            "description" => $this->desc,
            "category_id" => $this->category_id,
            "user_id" =>   $this->user_id  , 
            // "user_name" => User::find($this->user_id)->name ,
            "user_name" => $this->user->name ,
        ];
    }
}
