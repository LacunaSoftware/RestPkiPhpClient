<?php

namespace Lacuna\RestPki;

/**
 * Class RestPkiException
 * @package Lacuna\RestPki
 *
 * @property-read $errorCode string
 * @property-read $detail string
 */
class RestPkiException extends RestException
{

    /** @var string */
    private $_errorCode;

    /** @var string|null */
    private $_detail;

    /**
     * @param string $verb
     * @param string $url
     * @param string $errorCode
     * @param string $detail
     */
    public function __construct($verb, $url, $errorCode, $detail)
    {
        $message = "REST PKI action {$verb} {$url} error: {$errorCode}";
        if (!empty($detail)) {
            $message .= " ({$detail})";
        }
        parent::__construct($message, $verb, $url);
        $this->_errorCode = $errorCode;
        $this->_detail = $detail;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * @return string|null
     */
    public function getDetail()
    {
        return $this->_detail;
    }

    public function __get($name)
    {
        switch ($name) {
            case "errorCode":
                return $this->_errorCode;
            case "detail":
                return $this->_detail;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
