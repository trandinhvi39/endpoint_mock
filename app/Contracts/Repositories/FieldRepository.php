<?php

namespace App\Contracts\Repositories;

use App\Eloquent\Field;

interface FieldRepository extends AbstractRepository
{
    public function updateData(Field $field, array $fieldsName, array $fieldsValue);
}
