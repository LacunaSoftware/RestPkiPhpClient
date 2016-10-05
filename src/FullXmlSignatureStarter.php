<?php

namespace Lacuna\RestPki\Client;

class FullXmlSignatureStarter extends XmlSignatureStarter
{

    public function __construct($restPkiClient)
    {
        parent::__construct($restPkiClient);
    }

    public function startWithWebPki()
    {

        parent::verifyCommonParameters(true);
        if (empty($this->xmlContent)) {
            throw new \Exception('The XML to sign was not set');
        }

        $request = parent::getRequest();

        $response = $this->restPkiClient->post('Api/XmlSignatures/FullXmlSignature', $request);

        if (isset($response->certificate)) {
            $this->certificateInfo = $response->certificate;
        }
        $this->done = true;

        return $response->token;
    }
}
