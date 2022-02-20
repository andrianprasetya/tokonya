<?php

/**
 * Copyright 2022 Odenktools Technology Open Source Project
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
use App\Libraries\ResponseStd;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class RoleController extends BaseApi
{
    /**
     * List all roles.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'roles.created_at';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            if ($limit > 25) {
                $limit = 10;
            }
            if (!empty($search_term)) {
                $conditions .= " AND roles.role_name ILIKE '%$search_term%'";
            }
            $paged = Role::sql()
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);
            $countAll = Role::query()->count();
            return ResponseStd::paginated($paged, $countAll);
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    protected function validateCreate(array $data)
    {
        $arrayValidator = [
            'role_name' => 'required|min:3|max:50|unique:roles,name,NULL,id',
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function validateUpdate(array $data)
    {
        $arrayValidator = [
            'id' => ['required', 'uuid'],
            'role_name' => 'required|min:3|max:150|unique:roles,name,' . $data['id'] . ',id',
        ];
        return Validator::make($data, $arrayValidator);
    }

    /**
     * Create a role.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateCreate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = Role::query()->create([
                'id' => Uuid::uuid4()->toString(),
                'name' => $request->input('role_name'),
                'created_at' => Carbon::now(),
            ]);
            DB::commit();
            // return successful response
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    /**
     * Display fields.
     *
     * @param $id
     * @return array|\Illuminate\Http\Response
     */
    public function show($id)
    {
        DB::beginTransaction();
        try {
            $model = Role::query()->find($id);
            if (!$model) {
                throw new \Exception("Invalid role id");
            }
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    /**
     * Update records.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateUpdate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = Role::query()->find($request->input('id'));
            if (!$model) {
                throw new \Exception("Invalid role id");
            }
            $model->update([
                'name' => $request->input('role_name'),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    /**
     * Remove records.
     *
     * @param $id
     * @return array|\Illuminate\Http\Response
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $model = Role::query()->find($id);
            if (!$model) {
                throw new \Exception("Invalid role id");
            }
            if ($model->user()->count() > 0) {
                throw new \Exception("cannot delete role, remove role on users first.");
            }
            $model->forceDelete();
            DB::commit();
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . $e->getLine() . ':' . __FUNCTION__ . ' ' . $e->getMessage());
                return ResponseStd::fail($e->getMessage());
            }
        }
    }
}
