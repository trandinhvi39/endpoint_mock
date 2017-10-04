<?php

namespace App\Services;

use App\Contracts\Services\GlideInterface;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

class GlideService implements GlideInterface
{
    public function getImageResponse($path, $params)
    {
        $server = app()['glide'];
        try {
            SignatureFactory::create(env('GLIDE_SIGNATURE_KEY'))->validateRequest($path, $params);

            if (!$server->sourceFileExists($path) && isset($params['p'])) {
                return $this->getImageDefault($params);
            }

            return $server->getImageResponse($path, $params);
        } catch (SignatureException $e) {
            \Log::error($e);

            return $this->getImageDefault($params);
        }
    }

    protected function getImageDefault($params)
    {
        $server = ServerFactory::create([
            'source' => public_path(),
            'cache' => storage_path('files/images'),
            'presets' => config('settings.image_size'),
            'response' => new LaravelResponseFactory(),
        ]);

        return $server->getImageResponse(config('settings.book_image_path_default'), $params);
    }
}
