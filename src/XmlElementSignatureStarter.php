<?php

namespace Lacuna\RestPkiClient;

/**
 * Class XmlElementSignatureStarter
 * @package Lacuna\RestPkiClient
 *
 * @property string $toSignElementId
 * @property XmlIdResolutionTable $idResolutionTable
 */
class XmlElementSignatureStarter extends XmlSignatureStarter
{

    public $toSignElementId;

    /** @var XmlIdResolutionTable */
    public $idResolutionTable;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * @param string $toSignElementId
     */
    public function setToSignElementId($toSignElementId)
    {
        $this->toSignElementId = $toSignElementId;
    }

    /**
     * @param XmlIdResolutionTable $idResolutionTable
     */
    public function setIdResolutionTable($idResolutionTable)
    {
        $this->idResolutionTable = $idResolutionTable;
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
        if (empty($this->toSignElementId)) {
            throw new \LogicException('The XML element Id to sign was not set');
        }

        $request = parent::getRequest();
        $request['elementToSignId'] = $this->toSignElementId;
        if ($this->idResolutionTable != null) {
            $request['idResolutionTable'] = $this->idResolutionTable->toModel();
        }

        $response = $this->client->post('Api/XmlSignatures/XmlElementSignature', $request);

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
        if (empty($this->toSignElementId)) {
            throw new \LogicException('The XML element Id to sign was not set');
        }

        $request = parent::getRequest();
        $request['elementToSignId'] = $this->toSignElementId;
        if ($this->idResolutionTable != null) {
            $request['idResolutionTable'] = $this->idResolutionTable->toModel();
        }

        $response = $this->client->post('Api/XmlSignatures/XmlElementSignature', $request);

        if (isset($response->certificate)) {
            $this->_certificateInfo = $response->certificate;
        }
        $this->done = true;

        return self::getClientSideInstructionsObject($response);
    }
}
