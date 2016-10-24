<?php

namespace Lacuna\RestPki\Client;

abstract class SignatureStarter
{

    /** @var RestPkiClient */
    protected $restPkiClient;
    protected $signerCertificateBase64;
    protected $signaturePolicyId;
    protected $securityContextId;
    protected $callbackArgument;
    protected $done;
    protected $certificateInfo;

    private static function getOpenSslSignatureAlgorithm($oid)
    {
        switch ($oid) {
            case '1.2.840.113549.2.5':
                return OPENSSL_ALGO_MD5;
            case '1.3.14.3.2.26':
                return OPENSSL_ALGO_SHA1;
            case '2.16.840.1.101.3.4.2.1':
                return OPENSSL_ALGO_SHA256;
            case '2.16.840.1.101.3.4.2.2':
                return OPENSSL_ALGO_SHA384;
            case '2.16.840.1.101.3.4.2.3':
                return OPENSSL_ALGO_SHA512;
            default:
                return null;
        }
    }

    protected static function getClientSideInstructionsObject($response)
    {
        return (object)array(
            'token' => $response->token,
            'toSignData' => base64_decode($response->toSignData),
            'toSignHash' => base64_decode($response->toSignHash),
            'digestAlgorithmOid' => $response->digestAlgorithmOid,
            'openSslSignatureAlgorithm' => self::getOpenSslSignatureAlgorithm($response->digestAlgorithmOid)
        );
    }

    protected function __construct($restPkiClient)
    {
        $this->restPkiClient = $restPkiClient;
    }

    public function setSignerCertificate($certificate)
    {
        $this->signerCertificateBase64 = base64_encode($certificate);
    }

    public function setSignerCertificateBase64($certificate)
    {
        $this->signerCertificateBase64 = $certificate;
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
            throw new \InvalidArgumentException("The getCertificateInfo() method can only be called after calling one of the start methods");
        }

        return $this->certificateInfo;
    }

    public abstract function startWithWebPki();

    public abstract function start();

}