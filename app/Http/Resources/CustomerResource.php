<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CustomerResource.
 *
 * Very useful for mapping standard response.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Http\Resources
 */
class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // Don`t use "deleted_at".
        return [
            'id' => $this->id,
            'email' => $this->email,
            'customer_code' => $this->customer_code,
            'customer_name' => $this->customer_name,
            'gender' => $this->gender,
            'customer_address' => $this->customer_address,
            'image_url' => $this->image_id ? asset($this->image->getFileUrlAttribute()) : null,
            'image_id' => $this->image_id,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
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
