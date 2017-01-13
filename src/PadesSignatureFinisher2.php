<?php

namespace Lacuna\RestPkiClient;

class PadesSignatureFinisher2 extends SignatureFinisher2
{
    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    protected function getApiRoute()
    {
        return "Api/v2/PadesSignatures/{$this->token}/SignedBytes";
    }
}
