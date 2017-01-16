<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignatureFinisher2
 * @package Lacuna\RestPki
 */
class PadesSignatureFinisher2 extends SignatureFinisher2
{
    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    protected function checkCompatibility()
    {
        if ($this->client->_getApiVersion('CompletePades') < 2) {
            throw new \LogicException("The PadesSignatureFinisher2 class can only be used with Rest PKI 1.11 or later. Either update your Rest PKI or use the older PadesSignatureFinisher class.");
        }
    }

    protected function getApiRoute()
    {
        return "Api/v2/PadesSignatures/{$this->token}/SignedBytes";
    }
}
