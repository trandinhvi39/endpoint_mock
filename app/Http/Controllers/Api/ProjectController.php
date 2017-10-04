<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\ProjectRepository;
use App\Http\Requests\Api\Project\StoreRequest;
use App\Http\Requests\Api\Project\UpdateRequest;

class ProjectController extends ApiController
{
    public function __construct(ProjectRepository $repository)
    {
        parent::__construct($repository);
    }

    public function index()
    {
        $this->before(__FUNCTION__);

        return $this->requestAction(function () {
            $this->compacts['items'] = $this->repository->where('user_id', $this->user->id)
                ->with(['endpoints', 'endpoints.method', 'endpoints.field'])->get();
        });
    }

    public function show($id)
    {
        return $this->requestAction(function () use ($id) {
            $this->compacts['item'] = $this->repository
                ->where('user_id', $this->user->id)
                ->with(['endpoints', 'endpoints.method', 'endpoints.field'])
                ->findOrFail($id);
        });
    }

    public function store(StoreRequest $request)
    {
        $data = $request->all();

        return $this->requestAction(function () use ($data) {
            $project = $this->repository->create($data);
        });
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->all();

        return $this->requestAction(function () use ($data, $id) {
            $project = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $project);
            $project->update($data);
        });
    }

    public function destroy($id)
    {
        return $this->doAction(function () use ($id) {
            $project = $this->repository->findOrFail($id);
            $this->before(__FUNCTION__, $project);
            $project->delete();
        });
    }
}
