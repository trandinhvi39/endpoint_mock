<?php

namespace App\Exceptions\Api;

class UnknownException extends ApiException
{
    public function __construct($message = null, $statusCode = 400)
    {
        $message = $message ? $message : translate('exception.unknown_error');

        parent::__construct($message, $statusCode);
    }
}
