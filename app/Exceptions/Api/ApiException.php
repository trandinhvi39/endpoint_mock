<?php

namespace App\Exceptions\Api;

use Exception;

abstract class ApiException extends Exception
{
    protected $statusCode = null;
    protected $description = null;
    protected $data = null;
    /**
     * Construct the ApiException
     * @param $description [required] The Exception message to throw.
     * @param int $statusCode [required] The Exception code.
     * @param array $data [optional] The Exception code.
     */
    public function __construct($description = null, $statusCode = null, $data = [])
    {
        if (!$statusCode || !is_numeric($statusCode)) {
            $statusCode = config('httpstatus.default_code');
        }
        if (!$description) {
            $description = translate('http_message.' . config('httpstatus.code.' . $statusCode));
        }

        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->description = $description;

        parent::__construct($description, $statusCode);
    }

    public function getErrorDescription()
    {
        return $this->getMessage();
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getData()
    {
        return $this->data;
    }
}
