<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureFinisher
 * @package Lacuna\RestPki
 *
 * @property string $token
 * @property string $callbackArgument
 * @property-read $certificateInfo
 */
abstract class SignatureFinisher
{
    public $token;
    public $callbackArgument;

    /** @var RestPkiClient */
    protected $client;

    /** @var string */
    protected $signatureBase64;

    /** @var bool */
    protected $done;

    protected $_certificateInfo;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param string $signature The binary encoded result of the signature algorithm
     */
    public function setSignature($signature)
    {
        $this->signatureBase64 = base64_encode($signature);
    }

    /**
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
     * @return string
     */
    public function getCallbackArgument()
    {
        if (!$this->done) {
            throw new \LogicException("The getCallbackArgument() method can only be called after calling the finish() method");
        }

        return $this->callbackArgument;
    }

    public function getCertificateInfo()
    {
        if (!$this->done) {
            throw new \LogicException('The method getCertificateInfo() can only be called after calling the finish() method');
        }

        return $this->_certificateInfo;
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
}
