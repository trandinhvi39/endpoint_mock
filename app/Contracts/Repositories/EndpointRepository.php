<?php

namespace App\Contracts\Repositories;

interface EndpointRepository extends AbstractRepository
{
    public function lengthParamsMapping(string $fieldsName, array $fieldsValue);
}
