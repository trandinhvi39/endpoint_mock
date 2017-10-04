<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Api\NotOwnerException;
use App\Http\Controllers\AbstractController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknownException;
use Exception;
use DB;
use Log;
use App\Exceptions\Api\ActionException;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class ApiController extends AbstractController
{
    protected $guard = 'api';

    protected $prefix = 'api.v1';

    protected $dataSelect = ['*'];

    protected function jsonRender($data = [])
    {
        $this->compacts['message'] = [
            'code' => 200,
            'status' => true,
        ];

        $compacts = array_merge($data, $this->compacts);

        return response()->json($compacts);
    }

    protected function getData(callable $callback, $code = 500)
    {
        try {
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
            }
        } catch (Exception $e) {
            throw new UnknownException($e->getMessage(), $e->getCode());
        }

        return $this->jsonRender();
    }

    public function show($id)
    {
        try {
            $item = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $item);

            $this->compacts['item'] = $item;
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());

            throw new NotFoundException();
        } catch (Exception $e) {
            Log::error($e->getMessage());

            throw new UnknownException($e->getMessage(), $e->getCode());
        }
    }

    protected function doAction(callable $callback, $action = null, $code = 500)
    {
        DB::beginTransaction();
        try {
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
            }

            DB::commit();
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            throw new NotFoundException();
        } catch (NotOwnerException $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            throw new NotOwnerException();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            if (in_array($action, config('settings.action_exception_method'))) {
                throw new ActionException($action);
            }

            throw new UnknownException($e->getMessage(), $e->getCode());
        }

        return $this->jsonRender();
    }

    protected function requestAction(callable $callback, $action = null, $code = 500)
    {
        try {
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
            }
        } catch (ModelNotFoundException $e) {
            Log::error($e->getMessage());

            throw new NotFoundException();
        } catch (NotOwnerException $e) {
            Log::error($e->getMessage());

            throw new NotOwnerException();
        } catch (Exception $e) {
            Log::error($e->getMessage());

            if (in_array($action, config('settings.action_exception_method'))) {
                throw new ActionException($action);
            }

            throw new UnknownException($e->getMessage(), $e->getCode());
        }

        return $this->jsonRender();
    }

    protected function reFormatPaginate(LengthAwarePaginator $paginate)
    {
        $currentPage = $paginate->currentPage();

        return [
            'total' => $paginate->total(),
            'per_page' => $paginate->perPage(),
            'current_page' => $currentPage,
            'next_page' => ($paginate->lastPage() > $currentPage) ? $currentPage + 1 : null,
            'prev_page' => $currentPage - 1 ?: null,
            'data' => $paginate->items(),
        ];
    }
}
