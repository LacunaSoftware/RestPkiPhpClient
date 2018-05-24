<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignatureExplorer
 * @package Lacuna\RestPki
 */
class PadesSignatureExplorer extends SignatureExplorer
{
    const PDF_MIME_TYPE = "application/pdf";

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * @return PadesSignature The signature information
     * @throws RestUnreachableException
     */
    public function open()
    {
        $request = $this->getRequest();

        $response = $this->client->post("Api/PadesSignatures/Open", $request);

        return new PadesSignature($response);
    }
}
