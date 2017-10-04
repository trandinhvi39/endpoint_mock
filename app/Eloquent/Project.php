<?php

namespace App\Eloquent;

class Project extends AbstractEloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image', 'user_id', 'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function endpoints()
    {
        return $this->hasMany(Endpoint::class);
    }

    public function generateToken()
    {
        return str_random(config('settings.length_token'));
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            $project->token = $this->generateToken();
        });

        static::deleting(function ($project) {
            $project->endpoints()->delete();
        });
    }
}
