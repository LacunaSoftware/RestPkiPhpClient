<?php

namespace Lacuna\RestPki\Client;

class RestException extends \Exception
{

    private $verb;
    private $url;

    public function __construct($message, $verb, $url, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->verb = $verb;
        $this->url = $url;
    }

    public function getVerb()
    {
        return $this->verb;
    }

    public function getUrl()
    {
        return $this->url;
    }

}