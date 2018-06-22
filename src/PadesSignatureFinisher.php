<?php

namespace Lacuna\RestPki;

/**
 * @deprecated Use PadesSignatureFinisher2 instead
 *
 * Class PadesSignatureFinisher
 * @package Lacuna\RestPki
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
        $this->certificateInfo = $response->certificate;
        $this->done = true;

        return $this->signedPdf;
    }

    /**
     * Returns the binary contents of the signed PDF file (must only be called after calling finish())
     *
     * @return string The binary contents of the signed PDF
     */
    public function getSignedPdf()
    {
        if (!$this->done) {
            throw new \LogicException("The method getSignedPdf() method can only be called after calling the finish() method");
        }

        return $this->signedPdf;
    }

    /**
     * Writes the signed PDF file to a local file (must only be called after calling finish())
     *
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
