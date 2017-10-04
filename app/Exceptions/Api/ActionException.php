<?php

namespace App\Exceptions\Api;

class ActionException extends ApiException
{
    public function __construct($action = null, $statusCode = 500)
    {
        $action = $action ? translate('exception.' . $action) : translate('exception.action');

        parent::__construct($action, $statusCode);
    }
}
