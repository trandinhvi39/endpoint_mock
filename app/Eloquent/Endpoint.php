<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
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
        'project_id', 'prefix', 'limit', 'format',
    ];

    public function method()
    {
        return $this->hasOne(Method::class);
    }

    public function field()
    {
        return $this->hasOne(Field::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($endpoint) {
            $endpoint->field()->delete();
            $endpoint->method()->delete();
        });
    }
}
