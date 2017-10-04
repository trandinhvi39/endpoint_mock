<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProjectRepository;
use App\Eloquent\Project;

class ProjectRepositoryEloquent extends AbstractRepositoryEloquent implements ProjectRepository
{
    public function model()
    {
        return app(Project::class);
    }

    /**
     * Create new project after append user_id
     *
     * @param $data
     * @return void
     */
    public function create($data)
    {
        $data['user_id'] = $this->user->id;
        $this->model()->create(array_only($data, [
            'name',
            'description',
            'image',
        ]));
    }

    /**
     * Update project after append user_id
     *
     * @param $data
     * @return void
     */
    public function update($data)
    {
        $data['user_id'] = $this->user->id;
        $this->model()->update(array_only($data, [
            'name',
            'description',
            'image',
        ]));
    }
}
