<?php

namespace Lacuna\RestPki\Client;

/**
 * @deprecated Use CadesSignatureFinisher2 instead
 *
 * Class CadesSignatureFinisher
 * @package Lacuna\RestPki\Client
 */
class CadesSignatureFinisher extends SignatureFinisher
{
    private $cms;

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
            $response = $this->client->post("Api/CadesSignatures/{$this->token}/Finalize", null);
        } else {
            $request['signature'] = $this->signatureBase64;
            $response = $this->client->post("Api/CadesSignatures/{$this->token}/SignedBytes", $request);
        }

        $this->cms = base64_decode($response->cms);
        $this->callbackArgument = $response->callbackArgument;
        $this->_certificateInfo = $response->certificate;
        $this->done = true;

        return $this->cms;
    }

    /**
     * @return string The binary encoded CMS (PKCS#7) file
     */
    public function getCms()
    {
        if (!$this->done) {
            throw new \LogicException("The getCms() method can only be called after calling the finish() method");
        }
        return $this->cms;
    }

    /**
     * @param string $path The path of the file on which to write the CMS (PKCS#7) file contents
     */
    public function writeCmsToPath($path)
    {
        if (!$this->done) {
            throw new \LogicException('The method writeCmsToPath() can only be called after calling the finish() method');
        }

        file_put_contents($path, $this->cms);
    }
}
