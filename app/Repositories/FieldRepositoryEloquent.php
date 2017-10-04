<?php

namespace App\Repositories;

use App\Contracts\Repositories\FieldRepository;
use App\Eloquent\Field;

class FieldRepositoryEloquent extends AbstractRepositoryEloquent implements FieldRepository
{
    public function model()
    {
        return app(Field::class);
    }

    /**
     * Update field data of fields table
     *
     * @param App\Eloquent\Field $field
     * @param array $fieldsName
     * @param array $fieldsValue
     * @return boolean
     */
    public function updateData(Field $field, array $fieldsName, array $fieldsValue)
    {
        $dataUpdate = json_decode($field->data);
        $currentFieldsName = $field->explodeFieldsName();

        foreach ($fieldsName as $key => $requestFieldsName) {
            foreach ($currentFieldsName as $fieldName) {
                if (isset($fieldsValue[$key])) {
                    $dataUpdate->$requestFieldsName = $fieldsValue[$key];
                }
            }
        }

        return $field->update([
            'data' => json_encode($dataUpdate),
        ]);
    }
}
