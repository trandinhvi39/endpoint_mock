<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'limit_project',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
