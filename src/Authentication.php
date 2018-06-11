<?php

namespace Lacuna\RestPki;

/**
 * Class Authentication
 * @package Lacuna\RestPki
 *
 * @property $ignoreRevocationStatusUnknown
 * @property-read $certificate
 */
class Authentication
{
    public $ignoreRevocationStatusUnknown = false;

    /** @var RestPkiClient */
    private $client;

    private $_certificate;
    private $done = false;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param string $securityContextId
     * @return string
     * @throws RestUnreachableException
     */
    public function startWithWebPki($securityContextId)
    {
        $request = array(
            'securityContextId' => $securityContextId,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown
        );

        $response = $this->client->post('Api/Authentications', $request);

        return $response->token;
    }

    /**
     * @param string $token
     * @return ValidationResults
     * @throws RestUnreachableException
     */
    public function completeWithWebPki($token)
    {
        $response = $this->client->post("Api/Authentications/$token/Finalize", null);
        if (isset($response->certificate)) {
            $this->_certificate = $response->certificate;
        }
        $this->done = true;

        return new ValidationResults($response->validationResults);
    }

    public function getCertificate()
    {
        if (!$this->done) {
            throw new \LogicException('The method getCertificate() can only be called after calling the completeWithWebPki method');
        }
        return $this->_certificate;
    }

    public function __get($name)
    {
        switch ($name) {
            case "certificate":
                return $this->getCertificate();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }

    public function __isset($name)
    {
        switch ($name) {
            case "certificate":
                return isset($this->_certificate);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
