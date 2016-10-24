<?php

namespace Lacuna\RestPki\Client;

abstract class SignatureFinisher
{

    /** @var RestPkiClient */
    protected $restPkiClient;
    protected $token;
    protected $signatureBase64;
    protected $done;
    protected $callbackArgument;
    protected $certificateInfo;

    public function __construct($restPkiClient)
    {
        $this->restPkiClient = $restPkiClient;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setSignature($signature)
    {
        $this->signatureBase64 = base64_encode($signature);
    }

    public function setSignatureBase64($signature)
    {
        $this->signatureBase64 = $signature;
    }

    public abstract function finish();

    public function getCallbackArgument()
    {
        if (!$this->done) {
            throw new \Exception("The getCallbackArgument() method can only be called after calling the finish() method");
        }

        return $this->callbackArgument;
    }

    public function getCertificateInfo()
    {
        if (!$this->done) {
            throw new \Exception('The method getCertificateInfo() can only be called after calling the finish() method');
        }

        return $this->certificateInfo;
    }
}