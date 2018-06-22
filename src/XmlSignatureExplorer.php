<?php

namespace Lacuna\RestPki;

/**
 * Class XmlSignatureExplorer
 * @package Lacuna\RestPki
 */
class XmlSignatureExplorer extends SignatureExplorer
{
    const XML_MIME_TYPE = "application/xml";

    /** @var  XmlIdResolutionTable */
    private $idResolutionTable;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * @return XmlSignature[] The signatures informations
     * @throws RestUnreachableException
     */
    public function open()
    {
        $request = parent::getRequest();
        $request['idResolutionTable'] = $this->idResolutionTable;

        $response = $this->client->post("Api/XmlSignatures/Open", $request);

        $signatures = [];
        foreach ($response as $signature) {
            $signatures[] = new XmlSignature($signature);
        }
        return $signatures;
    }
}