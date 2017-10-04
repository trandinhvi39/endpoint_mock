<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\FieldRepository;
use App\Eloquent\Field;

class FieldController extends ApiController
{
    public function __construct(FieldRepository $repository)
    {
        parent::__construct($repository);
    }

    public function update(Request $request, $id)
    {
        return $this->requestAction(function () use ($id) {
            $field = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $field);
            $field->updateMockData();
        });
    }

    public function destroy($id)
    {
        return $this->requestAction(function () use ($id) {
            $field = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $field);
            $field->resetData();
        });
    }
}
