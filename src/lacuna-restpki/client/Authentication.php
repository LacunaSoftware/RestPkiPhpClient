<?php

namespace Lacuna\RestPki\Client;

class Authentication
{

    /** @var RestPkiClient */
    private $restPkiClient;

    private $certificate;
    private $done;

    public function __construct($restPkiClient)
    {
        $this->restPkiClient = $restPkiClient;
        $this->done = false;
    }

    public function startWithWebPki($securityContextId)
    {
        $response = $this->restPkiClient->post('Api/Authentications', array(
            'securityContextId' => $securityContextId
        ));
        return $response->token;
    }

    public function completeWithWebPki($token)
    {
        $response = $this->restPkiClient->post("Api/Authentications/$token/Finalize", null);
        $this->certificate = $response->certificate;
        $this->done = true;
        return new ValidationResults($response->validationResults);
    }

    public function getCertificate()
    {
        if (!$this->done) {
            throw new \Exception('The method getCertificate() can only be called after calling the completeWithWebPki method');
        }
        return $this->certificate;
    }

}