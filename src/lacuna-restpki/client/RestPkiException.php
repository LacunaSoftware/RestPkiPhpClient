<?php

namespace Lacuna\RestPki\Client;

class RestPkiException extends RestException
{

    private $errorCode;
    private $detail;

    public function __construct($verb, $url, $errorCode, $detail)
    {
        $message = "REST PKI action {$verb} {$url} error: {$errorCode}";
        if (!empty($detail)) {
            $message .= " ({$detail})";
        }
        parent::__construct($message, $verb, $url);
        $this->errorCode = $errorCode;
        $this->detail = $detail;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getDetail()
    {
        return $this->detail;
    }
}