<?php

namespace Lacuna\RestPki\Client;

/**
 * Class FullXmlSignatureStarter
 * @package Lacuna\RestPki\Client
 */
class FullXmlSignatureStarter extends XmlSignatureStarter
{

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * @return string
     * @throws RestUnreachableException
     */
    public function startWithWebPki()
    {

        parent::verifyCommonParameters(true);
        if (empty($this->xmlToSign)) {
            throw new \LogicException('The XML was not set');
        }

        $request = parent::getRequest();

        $response = $this->client->post('Api/XmlSignatures/FullXmlSignature', $request);

        if (isset($response->certificate)) {
            $this->_certificateInfo = $response->certificate;
        }
        $this->done = true;

        return $response->token;
    }

    /**
     * @return SignatureAlgorithmParameters
     * @throws RestUnreachableException
     */
    public function start()
    {
        parent::verifyCommonParameters(false);
        if (empty($this->xmlToSign)) {
            throw new \LogicException('The XML was not set');
        }

        $request = parent::getRequest();

        $response = $this->client->post('Api/XmlSignatures/FullXmlSignature', $request);

        if (isset($response->certificate)) {
            $this->_certificateInfo = $response->certificate;
        }
        $this->done = true;

        return self::getClientSideInstructionsObject($response);
    }
}
