<?php

namespace Lacuna\RestPkiClient;

/**
 * @deprecated Use PadesSignatureFinisher2 instead
 *
 * Class PadesSignatureFinisher
 * @package Lacuna\RestPkiClient
 */
class PadesSignatureFinisher extends SignatureFinisher
{
    private $signedPdf;

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
            $response = $this->client->post("Api/PadesSignatures/{$this->token}/Finalize", null);
        } else {
            $request['signature'] = $this->signatureBase64;
            $response = $this->client->post("Api/PadesSignatures/{$this->token}/SignedBytes", $request);
        }

        $this->signedPdf = base64_decode($response->signedPdf);
        $this->callbackArgument = $response->callbackArgument;
        $this->_certificateInfo = $response->certificate;
        $this->done = true;

        return $this->signedPdf;
    }

    /**
     * @return string The binary contents of the signed PDF
     */
    public function getSignedPdf()
    {
        if (!$this->done) {
            throw new \LogicException("The getSignedPdf() method can only be called after calling the finish() method");
        }

        return $this->signedPdf;
    }

    /**
     * @param string $pdfPath The path of the file on which to write the signed PDF
     */
    public function writeSignedPdfToPath($pdfPath)
    {
        if (!$this->done) {
            throw new \LogicException('The method writeSignedPdfToPath() can only be called after calling the finish() method');
        }

        file_put_contents($pdfPath, $this->signedPdf);
    }
}
