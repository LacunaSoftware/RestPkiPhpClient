<?php

namespace Lacuna\RestPki\Client;

/**
 * Class RestErrorException
 * @package Lacuna\RestPki\Client
 *
 * @property-read $statusCode int
 * @property-read $errorMessage string
 */
class RestErrorException extends RestException
{
    /** @var int */
    private $_statusCode;

    /** @var string */
    private $_errorMessage;

    /**
     * @param string $verb
     * @param string $url
     * @param int $statusCode
     * @param string|null $errorMessage
     */
    public function __construct($verb, $url, $statusCode, $errorMessage = null)
    {
        $message = "REST action {$verb} {$url} returned HTTP error {$statusCode}";
        if (!empty($errorMessage)) {
            $message .= ": {$errorMessage}";
        }
        parent::__construct($message, $verb, $url);
        $this->_statusCode = $statusCode;
        $this->_errorMessage = $errorMessage;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    public function __get($name)
    {
        switch ($name) {
            case "statusCode":
                return $this->_statusCode;
            case "errorMessage":
                return $this->_errorMessage;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
