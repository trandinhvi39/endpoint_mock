<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Repositories\UserRepository;
use App\Eloquent\Endpoint;
use App\Eloquent\Field;
use App\Eloquent\Method;
use App\Eloquent\Project;

class UserController extends ApiController
{
    protected $levelSelect = ['id', 'name', 'limit_project'];

    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function showProfile()
    {
        return $this->requestAction(function () {
            $this->compacts['item'] = $this->user->load([
                'level' => function($query) {
                    $query->select($this->levelSelect);
                },
            ]);
        });
    }
}
