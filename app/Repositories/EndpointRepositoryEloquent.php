<?php

namespace App\Repositories;

use App\Contracts\Repositories\EndpointRepository;
use App\Eloquent\Endpoint;

class EndpointRepositoryEloquent extends AbstractRepositoryEloquent implements EndpointRepository
{
    public function model()
    {
        return app(Endpoint::class);
    }

    /**
     * Check mapping length of fields name and fields value
     *
     * @param string $fieldsName
     * @param array $fieldsValue
     * @return boolean
     */
    public function lengthParamsMapping(string $fieldsName, array $fieldsValue)
    {
        return count(explode(', ', $fieldsName)) == count($fieldsValue);
    }
}
