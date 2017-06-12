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

    public function open()
    {
        $request = parent::getRequest();
        $request['idResolutionTable'] = $this->idResolutionTable == null ? null : $this->idResolutionTable;

        $response = $this->client->post("Api/XmlSignatures/Open", $request);

        foreach ($response as $signature) {
            $signature->validationResults = new ValidationResults($signature->validationResults);
            $signature->signature = SignatureAlgorithm::getInstanceByApiAlgorithm($signature->signature->algorithmIdentifier);
            if (isset($signature->signingTime)) {
                $signature->signingTime = date("d/m/Y H:i:s P", strtotime($signature->signingTime));
            }
            if (isset($signature->certifiedDateReference)) {
                $signature->certifiedDateReference = date("d/m/Y H:i:s P", strtotime($signature->certifiedDateReference));
            }
        }
    }
}