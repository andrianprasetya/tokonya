<?php

/**
 * Copyright 2021 Odenktools Technology Open Source Project
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Http\Resources\CategoryResource;
use App\Libraries\FilesLibrary;
use App\Libraries\NumberLibrary;
use App\Libraries\ResponseStd;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

/**
 * Class CategoryController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Http\Controllers\Api
 */
class CategoryController extends BaseApi
{

    /**
     * List data.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {

            $search_term = $request->input('search');

            // default data size to display
            $limit = $request->has('limit') ? (int)$request->input('limit') : 50;

            $sort = $request->has('sort') ? $request->input('sort') : 'categories.created_at';

            $order = $request->has('order') ? $request->input('order') : 'DESC';

            $is_active = $request->has('is_active') ? (int)$request->input('is_active') : 1;

            $conditions = '1 = 1';

            if (!empty($search_term)) {
                $conditions .= " AND categories.category_name ILIKE '%$search_term%'";
                $conditions .= " OR categories.category_code ILIKE '%$search_term%'";
            }

            $conditions .= " AND categories.is_active = $is_active";

            // force limit
            if ($limit > 50) {
                $limit = 50;
            }

            $select = Category::query()
                ->select([
                    'categories.id',
                    'categories.category_code',
                    'categories.category_name',
                    'categories.category_description',
                    'categories.parent_id',
                    'categories.image_id',
                    'categories.is_active',
                    'categories.created_at',
                    'categories.updated_at',
                    'categories.deleted_at',
                ])
                ->whereRaw($conditions)
                ->orderBy($sort, $order);

            // counting all categories
            $countAll = Category::query()->count();

            // Pagination categories
            $paginate = $select->paginate($limit);

            // paging response.
            $response = CategoryResource::collection($paginate);

            // return json response.
            return ResponseStd::pagedFrom($response, $paginate, $countAll, 200);

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ":" . __FUNCTION__ . ':' . $e->getLine() . ':' . $e->getMessage());
            if ($e instanceof QueryException) {
                return ResponseStd::fail(trans('error.global.invalid-query'));
            } else {
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    protected function create(array $data, Request $request)
    {
        $categoryDescription = !empty($data['category_description']) ? $data['category_description'] : null;
        $parentId = !empty($data['parent_id']) ? $data['parent_id'] : null;

        // Input data image
        $dataImageId = null;

        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage($request->file($key),
                            'images/category',
                            false,
                            300,
                            300,
                            'category');
                    $dataImageId = $imageId;
                }
            } else {
                $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                $dataImageId = $key_id;
            }
        }
        // create a new category
        $categoryModel = Category::query()->create([
            'id' => Uuid::uuid4()->toString(),
            'category_code' => NumberLibrary::randomName($data['category_name']),
            'category_name' => $data['category_name'],
            'category_description' => $categoryDescription,
            'parent_id' => $parentId,
            'is_active' => $data['is_active'],
            'image_id' => $dataImageId,
            'created_at' => time(),
        ]);

        return $categoryModel;
    }

    /**
     * Validate create.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createValidator(array $data)
    {
        $arrayValidator = [
            'category_name' => [
                'required',
                'min:3',
                'max:70',
                'unique:categories,category_name'
            ],
            'category_description' => ['present'],
            'parent_id' => ['present'],
            'is_active' => ['required', 'integer', 'min:0', 'max:1'],
        ];

        return Validator::make($data, $arrayValidator);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->createValidator($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->create($request->all(), $request);
            DB::commit();

            // return response.
            $single = new CategoryResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage());
                }
            }
        }
    }
}
