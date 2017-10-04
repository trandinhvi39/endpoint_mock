<?php

namespace App\Contracts\Services;

interface GlideInterface
{
    public function getImageResponse($path, $params);
}
