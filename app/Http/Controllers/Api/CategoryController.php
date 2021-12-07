<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Http\Resources\CategoryResource;
use App\Libraries\ResponseStd;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends BaseApi
{
    /**
     * @param Request $request
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? (int)$request->input('limit') : 50;
            $ref = $request->has('ref') ? (int)$request->input('ref') : 0;
            $sort = $request->has('sort') ? $request->input('sort') : 'categories.created_at';
            $order = $request->has('order') ? $request->input('order') : 'DESC';
            $is_active = $request->has('is_active') ? (int)$request->input('is_active') : null;
            $conditions = '1 = 1';
            if ($ref === 1) {
                $conditions .= " AND categories.is_active = 1";
            }
            if (!empty($search_term)) {
                $conditions .= " AND categories.category_name ILIKE '%$search_term%'";
                $conditions .= " OR categories.category_code ILIKE '%$search_term%'";
            }
            if (!is_null($is_active)) {
                $conditions .= " AND categories.is_active = '$is_active'";
            }
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
            $countAll = Category::query()->count();
            $paginate = $select->paginate($limit);

            // paging response.
            $response = CategoryResource::collection($paginate);
            return ResponseStd::pagedFrom($response, $paginate, $countAll, 200);

        } catch (\Exception $e) {
            Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
            if ($e instanceof QueryException) {
                return ResponseStd::fail(trans('error.global.invalid-query'));
            } else {
                return ResponseStd::fail($e->getMessage());
            }
        }
    }
}
