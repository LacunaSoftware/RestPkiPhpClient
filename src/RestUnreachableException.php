<?php

namespace Lacuna\RestPkiClient;

/**
 * Class RestUnreachableException
 * @package Lacuna\RestPkiClient
 */
class RestUnreachableException extends RestException
{
    public function __construct($verb, $url, \Exception $previous)
    {
        parent::__construct("REST action {$verb} {$url} unreachable", $verb, $url, $previous);
    }
}
