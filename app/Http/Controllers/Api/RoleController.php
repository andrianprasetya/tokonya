<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApi;
use App\Libraries\ResponseStd;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

/**
 * Class RoleController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Http\Controllers\Api
 */
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
            $countAll = Role::count();
            return ResponseStd::paginated($paged, $countAll);
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    protected function validateCreate(array $data)
    {
        $arrayValidator = [
            'role_name' => 'required|min:3|max:50|unique:roles,role_name,NULL,id',
            'role_desc' => ['max:191'],
            'is_active' => ['required', 'boolean'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function validateUpdate(array $data)
    {
        $arrayValidator = [
            'id' => ['required', 'uuid'],
            'role_name' => 'required|min:3|max:150|unique:roles,role_name,' . $data['id'] . ',id',
            'slug' => 'required|unique:roles,slug,' . $data['id'] . ',id',
            'role_desc' => ['max:191'],
            'is_active' => ['required', 'boolean'],
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
            $model = new Role();
            $id = Uuid::uuid4()->toString();
            $model->id = $id;
            $model->role_name = $request->input('role_name');
            $model->slug = Str::slug($request->input('role_name'));
            $model->role_desc = $request->input('role_desc');
            $model->is_active = $request->input('is_active') ? true : false;
            $model->is_default = 0;
            $model->created_at = Carbon::now();
            // Save
            $model->save();
            DB::commit();
            // return successful response
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
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
        Log::info(json_encode($request->all()));
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
            $active = true;
            $slug = $model->slug;
            if (!$model->is_default) {
                $active = $request->input('is_active') ? true : false;
                $slug = $request->input('slug') ? $request->input('slug') : null;
            }
            $model->update([
                'role_name' => $request->input('role_name'),
                'slug' => $slug,
                'role_desc' => $request->input('role_desc') ? $request->input('role_desc') : null,
                'is_active' => $active,
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                return ResponseStd::fail($e->getMessage());
            }
        }
    }

    /**
     * Remove records.
     *
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $model = Role::query()->find($id);
            if (!$model) {
                throw new \Exception("Invalid role id");
            }
            if ($model->is_default === 1) {
                throw new \Exception("cannot delete role, it`s default role.");
            }
            if ($model->user()->count() > 0) {
                throw new \Exception("cannot delete role, remove role on users first.");
            }
            $model->forceDelete();
            DB::commit();
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseStd::fail($e->getMessage());
        }
    }

    public function changeStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $model = Role::query()->find($id);
            if (!$model) {
                throw new \Exception("Invalid role id");
            }
            if ($model->is_default === false) {
                $model->is_active = $status;
            }
            $model->update();
            DB::commit();
            return ResponseStd::okSingle($model);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseStd::fail($e->getMessage());
        }
    }
}
