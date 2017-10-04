<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\EndpointRepository;
use App\Eloquent\Endpoint;
use App\Http\Requests\Api\Endpoint\UpdateRequest;
use App\Exceptions\Api\ActionException;
use App\Contracts\Repositories\FieldRepository;

class EndpointController extends ApiController
{
    public function __construct(EndpointRepository $repository)
    {
        parent::__construct($repository);
    }

    public function lengthParamsMapping($fieldsName, $fieldsValue)
    {
        return count(explode(', ', $fieldsName)) == count($fieldsValue);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->only([
            'fields_name', 'fields_value',
        ]);

        if (! $this->lengthParamsMapping($data['fields_name'], $data['fields_value'])) {
            throw new ActionException('data_not_mapping');
        }

        return $this->requestAction(function () use ($data, $id) {
            $endpoint = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $endpoint);
            $field = $endpoint->field()->first();
            app(FieldRepository::class)->updateData($field, explode(', ', $data['fields_name']), $data['fields_value']);
        });
    }

    public function destroy($id)
    {
        return $this->doAction(function () use ($id) {
            $endpoint = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $endpoint);
            $endpoint->delete();
        });
    }
}
