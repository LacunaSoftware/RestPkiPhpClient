<?php

namespace Lacuna\RestPki\Client;

class RestErrorException extends RestException
{

    private $statusCode;
    private $errorMessage;

    public function __construct($verb, $url, $statusCode, $errorMessage = null)
    {
        $message = "REST action {$verb} {$url} returned HTTP error {$statusCode}";
        if (!empty($errorMessage)) {
            $message .= ": {$errorMessage}";
        }
        parent::__construct($message, $verb, $url);
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}