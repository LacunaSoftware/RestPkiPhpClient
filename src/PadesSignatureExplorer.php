<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignatureExplorer
 * @package Lacuna\RestPki
 */
class PadesSignatureExplorer extends SignatureExplorer
{
    const PDF_MIME_TYPE = "application/pdf";

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * @return mixed The signature information
     * @throws RestUnreachableException
     */
    public function open()
    {
        $request = $this->getRequest();

        $response = $this->client->post("Api/PadesSignatures/Open", $request);

        foreach ($response->signers as $signer) {
            $signer->validationResults = new ValidationResults($signer->validationResults);
            $signer->messageDigest->algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($signer->messageDigest->algorithm);
            if (isset($signer->signingTime)) {
                $signer->signingTime = date("d/m/Y H:i:s P", strtotime($signer->signingTime));
            }
            if (isset($signer->certificate)) {
                if (isset($signer->certificate->pkiBrazil)) {

                    if (isset($signer->certificate->pkiBrazil->cpf)) {
                        $cpf = $signer->certificate->pkiBrazil->cpf;
                        $signer->certificate->pkiBrazil->cpfFormatted = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3)
                            . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
                    } else {
                        $signer->certificate->pkiBrazil->cpfFormatted = '';
                    }

                    if (isset($signer->certificate->pkiBrazil->cnpj)) {
                        $cnpj = $signer->certificate->pkiBrazil->cnpj;
                        $signer->certificate->pkiBrazil->cnpjFormatted = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3)
                            . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12);
                    } else {
                        $signer->certificate->pkiBrazil->cnpjFormatted = '';
                    }
                }
            }
        }

        return $response;
    }
}
