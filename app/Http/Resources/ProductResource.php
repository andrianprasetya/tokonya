<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Class ProductResource.
     *
     * Very useful for mapping standard response.
     *
     * @author Odenktools Technology
     * @license MIT
     * @copyright (c) 2022, Odenktools Technology.
     *
     * @package App\Http\Resources
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'product_description'=> $this->product_description,
            'stock' => $this->stock,
            'subtract' => $this->subtract,
            'is_active' => $this->is_active,
            'image_url' => $this->image_id ? asset($this->image->getFileUrlAttribute()) : null,
            'image_url2' => $this->image_id2 ? asset($this->image2->getFileUrlAttribute()) : null,
            'image_url3' => $this->image_id3 ? asset($this->image3->getFileUrlAttribute()) : null,
            'image_url4' => $this->image_id4 ? asset($this->image4->getFileUrlAttribute()) : null,
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {

    }
}
