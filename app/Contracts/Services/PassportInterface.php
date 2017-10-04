<?php

namespace App\Contracts\Services;

interface PassportInterface
{
    public function passwordGrantToken(array $input, $scope = '');

    public function refreshGrantToken($refreshToken);
}
