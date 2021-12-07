<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 * Class CategoryResource.
 *
 * Very useful for mapping standard response.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Http\Resources
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // LAZY LOAD
        //$productCount = DB::table('products')->where('category_id', '=', $this->id)->count();

        // Jangan mempergunakan "deleted_at" pada mapping.
        return [
            'id' => $this->id,
            'category_code' => $this->category_code,
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
            'parent_id' => $this->parent_id,
            'image_url' => $this->image_id ? asset($this->image->getFileUrlAttribute()) : null,
            'is_active' => $this->is_active,
            //'product_count' => $productCount,
            'image' => $this->image,
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
