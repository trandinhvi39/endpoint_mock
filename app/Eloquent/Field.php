<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Eloquent\User;

class Field extends Model
{
    CONST DATA_TYPE = [
        'string',
        'text',
        'image',
        'boolean',
        'array',
        'date_time',
        'number',
        'time',
        'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endpoint_id', 'fields_name', 'fields_type', 'data',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }

    public function explodeFieldsName()
    {
        return explode(', ', $this->fields_name);
    }

    public function explodeFieldsType()
    {
        return explode(', ', $this->fields_type);
    }

    public function checkMappingNameAndType()
    {
        $fieldsName = $this->explodeFieldsName();
        $fieldsType = $this->explodeFieldsType();

        if (count($fieldsName) !== count($fieldsType)
            || count(array_intersect($fieldsType, self::DATA_TYPE)) !== count($fieldsType)) {
            return false;
        }

        return true;
    }

    public function fakerWithDataType(string $type)
    {
        $faker = \Faker\Factory::create();

        switch ($type) {
            case 'string':
                return $faker->sentence(1);
            case 'text':
                return $faker->text(10);
            case 'image':
                return $faker->imageUrl;
            case 'boolean':
                return $faker->boolean;
            case 'array':
                return '[]';
            case 'date_time':
                return $faker->dateTime;
            case 'number':
                return $faker->randomDigit;
            case 'time':
                return $faker->time;
            case 'object':
                return "{}";
            default:
                return null;
        }
    }

    public function mockData()
    {
        $fieldsName = $this->explodeFieldsName();
        $fieldsType = $this->explodeFieldsType();
        $mockedData = [];
        if ($this->checkMappingNameAndType()) {
            foreach($fieldsName as $key => $fieldName) {
                $mockedData[$fieldName] = $this->fakerWithDataType($fieldsType[$key]);
            }
        }

        return json_encode($mockedData);
    }

    public function resetData()
    {
        return $this->update([
            'data' => "",
        ]);
    }

    public function updateMockData()
    {
        return $this->update([
            'data' => $this->mockData(),
        ]);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($field) {
            $field->updateMockData();
        });
    }
}
