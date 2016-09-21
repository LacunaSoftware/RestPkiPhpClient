<?php

namespace Lacuna\RestPki\Client;

class XmlSignatureFinisher extends SignatureFinisher
{

    private $signedXml;

    public function __construct($restPkiClient)
    {
        parent::__construct($restPkiClient);
    }

    public function finish()
    {

        if (empty($this->token)) {
            throw new \Exception("The token was not set");
        }

        $response = $this->restPkiClient->post("Api/XmlSignatures/{$this->token}/Finalize", null);

        $this->signedXml = base64_decode($response->signedXml);
        $this->certificateInfo = $response->certificate;
        $this->done = true;

        return $this->signedXml;
    }

    public function writeSignedXmlToPath($xmlPath)
    {
        if (!$this->done) {
            throw new \Exception('The method writeSignedXmlToPath() can only be called after calling the finish() method');
        }
        file_put_contents($xmlPath, $this->signedXml);
    }
}