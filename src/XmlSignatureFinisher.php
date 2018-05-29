<?php

namespace Lacuna\RestPki;

/**
 * Class XmlSignatureFinisher
 * @package Lacuna\RestPki
 */
class XmlSignatureFinisher extends SignatureFinisher
{
    private $signedXml;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    public function finish()
    {
        $request = null;

        if (empty($this->token)) {
            throw new \Exception("The token was not set");
        }

        if (empty($this->signatureBase64)) {
            $response = $this->client->post("Api/XmlSignatures/{$this->token}/Finalize", null);
        } else {
            $request['signature'] = $this->signatureBase64;
            $response = $this->client->post("Api/XmlSignatures/{$this->token}/SignedBytes", $request);
        }

        $this->signedXml = base64_decode($response->signedXml);
        $this->callbackArgument = $response->callbackArgument;
        $this->certificateInfo = $response->certificate;
        $this->done = true;

        return $this->signedXml;
    }

    /**
     * Returns the encoded signed XML (can only be called after calling finish())
     *
     * @return string The encoded signed XML
     */
    public function getSignedXml()
    {
        if (!$this->done) {
            throw new \LogicException('The getSignedXml() method can only be called after calling the finish() method');
        }
        return $this->signedXml;
    }

    /**
     * Writes the signed XML to a local file (can only be called after calling finish())
     *
     * @param $xmlPath Path of the file on which to write the signed XML
     */
    public function writeSignedXmlToPath($xmlPath)
    {
        if (!$this->done) {
            throw new \LogicException('The method writeSignedXmlToPath() can only be called after calling the finish() method');
        }
        file_put_contents($xmlPath, $this->signedXml);
    }
}
