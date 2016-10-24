<?php

namespace Lacuna\RestPki\Client;

class CadesSignatureFinisher extends SignatureFinisher
{

    private $cms;

    public function __construct($restPkiClient)
    {
        parent::__construct($restPkiClient);
    }

    public function finish()
    {

        $request = null;

        if (empty($this->token)) {
            throw new \Exception("The token was not set");
        }

        if (empty($this->signature)) {
            $response = $this->restPkiClient->post("Api/CadesSignatures/{$this->token}/Finalize", null);
        } else {
            $request['signature'] = $this->signatureBase64;
            $response = $this->restPkiClient->post("Api/CadesSignatures/{$this->token}/SignedBytes", $request);
        }

        $this->cms = base64_decode($response->cms);
        $this->callbackArgument = $response->callbackArgument;
        $this->certificateInfo = $response->certificate;
        $this->done = true;

        return $this->cms;
    }

    public function getCms()
    {
        if (!$this->done) {
            throw new \Exception("The getCms() method can only be called after calling the finish() method");
        }
        return $this->cms;
    }

    public function writeCmsToPath($path)
    {
        if (!$this->done) {
            throw new \Exception('The method writeCmsToPath() can only be called after calling the finish() method');
        }

        file_put_contents($path, $this->cms);
    }
}