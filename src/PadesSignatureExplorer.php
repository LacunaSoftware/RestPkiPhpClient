<?php

namespace Lacuna\RestPki\Client;

class PadesSignatureExplorer extends SignatureExplorer
{
    const PDF_MIME_TYPE = "application/pdf";

    public function __construct($client)
    {
        parent::__construct($client);
    }

    public function open()
    {
        if (empty($this->signatureFileContent)) {
            throw new \RuntimeException("The signature file to open not set");
        } else {
            $request = $this->getRequest($this::PDF_MIME_TYPE);
            $response = $this->restPkiClient->post("Api/PadesSignatures/Open", $request);

            foreach ($response->signers as $signer) {
                $signer->validationResults = new ValidationResults($signer->validationResults);
                $signer->messageDigest->algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($signer->messageDigest->algorithm);

                if (isset($signer->signingTime)) {
                    $signer->signingTime = date("d/m/Y H:i:s P", strtotime($signer->signingTime));
                }
            }

            return $response;
        }
    }
}