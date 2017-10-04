<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'endpoint_id', 'index', 'create', 'show', 'update', 'delete'
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}
