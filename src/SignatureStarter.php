<?php

namespace Lacuna\RestPki\Client;

abstract class SignatureStarter
{

    /** @var RestPkiClient */
    protected $restPkiClient;
    public $signerCertificate;
    public $signaturePolicyId;
    public $securityContextId;
    public $callbackArgument;
    protected $done;
    protected $certificateInfo;

    protected function __construct($restPkiClient)
    {
        $this->restPkiClient = $restPkiClient;
    }

    public function setSignerCertificate($certificate)
    {
        $this->signerCertificate = $certificate;
    }

    public function setSignaturePolicy($signaturePolicyId)
    {
        $this->signaturePolicyId = $signaturePolicyId;
    }

    public function setSecurityContext($securityContextId)
    {
        $this->securityContextId = $securityContextId;
    }

    public function setCallbackArgument($callbackArgument)
    {
        $this->callbackArgument = $callbackArgument;
    }

    public function getCertificateInfo()
    {
        if (!$this->done) {
            throw new \InvalidArgumentException("The getCertificateInfo() method can only be called after calling the start() method");
        }

        return $this->certificateInfo;
    }

    public abstract function startWithWebPki();

}