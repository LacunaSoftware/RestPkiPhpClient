<?php

namespace Lacuna\RestPki;

/**
 * Class RestException
 * @package Lacuna\RestPki
 *
 * @property-read $verb string
 * @property-read $url string
 */
class RestException extends \Exception
{
    /** @var string */
    private $_verb;

    /** @var string */
    private $_url;

    /**
     * @param string $message
     * @param string $verb
     * @param string $url
     * @param \Exception|null $previous
     */
    public function __construct($message, $verb, $url, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->_verb = $verb;
        $this->_url = $url;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->_verb;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    public function __get($name)
    {
        switch ($name) {
            case "verb":
                return $this->_verb;
            case "url":
                return $this->_url;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }

    public function __isset($name)
    {
        switch ($name) {
            case "verb":
                return isset($this->_verb);
            case "url":
                return isset($this->_url);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
