<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureStarter
 * @package Lacuna\RestPki
 *
 * @property $signaturePolicy string
 * @property $securityContext string
 * @property $callbackArgument string
 * @property $ignoreRevocationStatusUnknown bool
 * @property-read $certificateInfo
*/
abstract class SignatureStarter
{
    public $signaturePolicy;
    public $securityContext;
    public $callbackArgument;
    public $ignoreRevocationStatusUnknown = false;

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
     * Sets the raw (binary) contents of the signer certificate
     *
     * @param string $certificate The binary contents of the signer certificate
     */
    public function setSignerCertificateRaw($certificate)
    {
        $this->signerCertificateBase64 = base64_encode($certificate);
    }

    /**
     * @deprecated Use function setSignerCertificateRaw
     *
     * @param string $certificate The raw (binary) contents of the signer certificate
     */
    public function setSignerCertificate($certificate)
    {
        $this->setSignerCertificateRaw($certificate);
    }

    /**
     * Sets the base64-encoded contents of the signer certificate
     *
     * @param string $certificate The base64-encoded contents of the signer certificate
     */
    public function setSignerCertificateBase64($certificate)
    {
        $this->signerCertificateBase64 = $certificate;
    }

    /**
     * Alias of setting the property `signaturePolicyId`
     *
     * @param string $signaturePolicyId
     */
    public function setSignaturePolicy($signaturePolicyId)
    {
        $this->signaturePolicy = $signaturePolicyId;
    }

    /**
     * Alias of setting the property `securityContext`
     *
     * @param string $securityContextId
     */
    public function setSecurityContext($securityContextId)
    {
        $this->securityContext = $securityContextId;
    }

    /**
     * Alias of setting the proprety `callbackArgument`
     *
     * @param string $callbackArgument
     */
    public function setCallbackArgument($callbackArgument)
    {
        $this->callbackArgument = $callbackArgument;
    }

    /**
     * Returns information about the signer's certificate if it was given in the input parameters (can only be called after calling one of the start methods)
     *
     * @return mixed
     */
    public function getCertificateInfo()
    {
        if (!$this->done) {
            throw new \LogicException("The getCertificateInfo() method can only be called after calling one of the start methods");
        }

        return $this->_certificateInfo;
    }

    /**
     * @return string
     */
    public abstract function startWithWebPki();

    /**
     * @return SignatureAlgorithmParameters
     */
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

    public function __isset($name)
    {
        switch ($name) {
            case "certificateInfo":
                return isset($this->_certificateInfo);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
