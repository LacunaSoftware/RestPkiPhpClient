<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignatureFinisher2
 * @package Lacuna\RestPki
 */
class CadesSignatureFinisher2 extends SignatureFinisher2
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
        if ($this->client->_getApiVersion('CompleteCades') < 2) {
            throw new \LogicException("The CadesSignatureFinisher2 class can only be used with Rest PKI 1.11 or later. Either update your Rest PKI or use the older CadesSignatureFinisher class.");
        }
    }

    protected function getApiRoute()
    {
        return "Api/v2/CadesSignatures/{$this->token}/SignedBytes";
    }
}
