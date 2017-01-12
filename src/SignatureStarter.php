<?php

namespace Lacuna\RestPki\Client;

/**
 * Class SignatureStarter
 * @package Lacuna\RestPki\Client
 *
 * @property $signaturePolicy string
 * @property $securityContext string
 * @property $callbackArgument string
 * @property-read $certificateInfo
 */
abstract class SignatureStarter
{
    public $signaturePolicy;
    public $securityContext;
    public $callbackArgument;

    /** @var RestPkiClient */
    protected $client;
    protected $signerCertificateBase64;
    protected $done;
    protected $_certificateInfo;

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

    /**
     * @param $response
     * @return SignatureAlgorithmParameters
     */
    protected static function getClientSideInstructionsObject($response)
    {
        $sigParams = new SignatureAlgorithmParameters();
        $sigParams->token = $response->token;
        $sigParams->toSignData = base64_decode($response->toSignData);
        $sigParams->toSignHash = base64_decode($response->toSignHash);
        $sigParams->digestAlgorithmOid = $response->digestAlgorithmOid;
        $sigParams->openSslSignatureAlgorithm = self::getOpenSslSignatureAlgorithm($response->digestAlgorithmOid);
        return $sigParams;
    }

    /**
     * @param $client RestPkiClient
     */
    protected function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param string $certificate The binary contents of the signer certificate
     */
    public function setSignerCertificate($certificate)
    {
        $this->signerCertificateBase64 = base64_encode($certificate);
    }

    /**
     * @param string $certificate The base64-encoded contents of the signer certificate
     */
    public function setSignerCertificateBase64($certificate)
    {
        $this->signerCertificateBase64 = $certificate;
    }

    /**
     * @param string $signaturePolicyId
     */
    public function setSignaturePolicy($signaturePolicyId)
    {
        $this->signaturePolicy = $signaturePolicyId;
    }

    /**
     * @param string $securityContextId
     */
    public function setSecurityContext($securityContextId)
    {
        $this->securityContext = $securityContextId;
    }

    /**
     * @param string $callbackArgument
     */
    public function setCallbackArgument($callbackArgument)
    {
        $this->callbackArgument = $callbackArgument;
    }

    public function getCertificateInfo()
    {
        if (!$this->done) {
            throw new \LogicException("The getCertificateInfo() method can only be called after calling one of the start methods");
        }

        return $this->_certificateInfo;
    }

    public abstract function startWithWebPki();

    public abstract function start();

    public function __get($name)
    {
        switch ($name) {
            case "certificateInfo":
                return $this->getCertificateInfo();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}