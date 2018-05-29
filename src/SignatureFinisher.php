<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureFinisher
 * @package Lacuna\RestPki
 *
 * @property string $token
 * @property string $signatureBase64
 */
abstract class SignatureFinisher
{
    public $token;
    public $signatureBase64;

    /** @var RestPkiClient */
    protected $client;

    /** @var bool */
    protected $done;

    /** @var string */
    protected $callbackArgument;

    protected $certificateInfo;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Alias of setting the property `token`
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @deprecated Use function setSignatureRaw
     *
     * @param string $signature The raw (binary) result of the signature algorithm
     */
    public function setSignature($signature)
    {
        $this->setSignatureRaw($signature);
    }

    /**
     * Sets the raw (binary) result of the signature algorithm
     *
     * @param string $signature The raw (binary) result of the signature algorithm
     */
    public function setSignatureRaw($signature)
    {
        $this->signatureBase64 = base64_encode($signature);
    }

    /**
     * Alias of setting the property `signatureBase64`
     *
     * @param $signature The base64-encoded result of the signature algorithm
     */
    public function setSignatureBase64($signature)
    {
        $this->signatureBase64 = $signature;
    }

    /**
     * @return string The binary encoded signed file
     */
    public abstract function finish();


    /**
     * Returns the callback argument passed when the signature process was started (can only be called after calling finish())
     *
     * @return string
     */
    public function getCallbackArgument()
    {
        if (!$this->done) {
            throw new \LogicException("The getCallbackArgument() method can only be called after calling the finish() method");
        }

        return $this->callbackArgument;
    }

    /**
     * Returns information about the signer's certificate (can only be called after calling finish())
     *
     * @return mixed
     */
    public function getCertificateInfo()
    {
        if (!$this->done) {
            throw new \LogicException('The method getCertificateInfo() can only be called after calling the finish() method');
        }

        return $this->certificateInfo;
    }

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
                return isset($this->certificateInfo);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
