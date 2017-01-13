<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureResult
 * @package Lacuna\RestPki
 */
class SignatureResult extends FileResult
{
    public $certificate;
    public $callbackArgument;

    /**
     * @internal
     *
     * @param RestPkiClient $restPkiClient
     * @param $model
     * @param $certificate
     * @param $callbackArgument
     */
    public function __construct($restPkiClient, $model, $certificate, $callbackArgument)
    {
        parent::__construct($restPkiClient, $model);
        $this->certificate = $certificate;
        $this->callbackArgument = $callbackArgument;
    }
}
