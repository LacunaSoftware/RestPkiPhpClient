<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureFinisher2
 * @package Lacuna\RestPki
 */
abstract class SignatureFinisher2
{
    /** @var string */
    public $token;

    /** @var string */
    public $signatureBase64;

    /** @var bool */
    public $forceBlobResult;

    /** @var RestPkiClient */
    protected $client;

    /**
     * @param $client RestPkiClient
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->forceBlobResult = false;
    }

    /**
     * Sets the signature algorithm output in binary form
     *
     * @param $signature string
     */
    public function setSignatureBinary($signature)
    {
        $this->signatureBase64 = base64_encode($signature);
    }

    /**
     * @return SignatureResult
     *
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws RestUnreachableException
     * @throws ValidationException
     * @throws \Exception
     */
    public function finish() {

        if (empty($this->token)) {
            throw new \Exception("The token was not set");
        }

        $request = array(
            'forceBlobResult' => $this->forceBlobResult,
            'signature' => $this->signatureBase64
        );
        $response = $this->client->post($this->getApiRoute(), $request);

        return new SignatureResult($this->client, $response->signatureFile, $response->certificate, $response->callbackArgument);
    }

    /**
     * @return string
     */
    protected abstract function getApiRoute();
}
