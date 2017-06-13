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
     * @return array The signatures informations
     * @throws RestUnreachableException
     */
    public function open()
    {
        $request = parent::getRequest();
        $request['idResolutionTable'] = $this->idResolutionTable == null ? null : $this->idResolutionTable;

        $response = $this->client->post("Api/XmlSignatures/Open", $request);

        foreach ($response as $signature) {
            $signature->validationResults = new ValidationResults($signature->validationResults);

            $signature->signature = array(
                'value' => base64_decode($signature->signature->value),
                'hexValue' => $signature->signature->hexValue,
                'algorithm' => SignatureAlgorithm::getInstanceByApiAlgorithm($signature->signature->algorithmIdentifier->algorithm)
            );

            if (isset($signature->signingTime)) {
                $signature->signingTime = date("d/m/Y H:i:s P", strtotime($signature->signingTime));
            }
            if (isset($signature->certifiedDateReference)) {
                $signature->certifiedDateReference = date("d/m/Y H:i:s P",
                    strtotime($signature->certifiedDateReference));
            }

            if (isset($signature->timestamps)) {
                foreach ($signature->timestamps as $timestamp) {
                    if (isset($timestamp->messageImprint)) {
                        $timestamp->messageImprint->algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($timestamp->messageImprint->algorithm);
                        $timestamp->messageImprint->value = base64_decode($timestamp->messageImprint->value);
                    }
                }
            }
        }

        return $response;
    }
}