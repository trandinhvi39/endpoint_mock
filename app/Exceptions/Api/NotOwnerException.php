<?php

namespace App\Exceptions\Api;

class NotOwnerException extends ApiException
{
    public function __construct($message = null, $statusCode = 400)
    {
        $message = $message ? $message : translate('exception.not_owner');

        parent::__construct($message, $statusCode);
    }
}
