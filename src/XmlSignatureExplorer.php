<?php

namespace Lacuna\RestPki;

/**
 * Class XmlSignatureExplorer
 * @package Lacuna\RestPki
 */
class XmlSignatureExplorer extends SignatureExplorer
{
    const XML_MIME_TYPE = "application/xml";

    /** @var  XmlIdResolutionTable */
    private $idResolutionTable;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    /**
     * @return array The signatures informations
     * @throws RestUnreachableException
     */
    public function open()
    {
        $request = parent::getRequest();
        $request['idResolutionTable'] = $this->idResolutionTable == null ? null : $this->idResolutionTable;

        $response = $this->client->post("Api/XmlSignatures/Open", $request);

        foreach ($response as $signature) {
            $signature->validationResults = new ValidationResults($signature->validationResults);

            $signature->signature = array(
                'value' => base64_decode($signature->signature->value),
                'hexValue' => $signature->signature->hexValue,
                'algorithm' => SignatureAlgorithm::getInstanceByApiAlgorithm($signature->signature->algorithmIdentifier->algorithm)
            );

            if (isset($signature->signingTime)) {
                $signature->signingTime = date("d/m/Y H:i:s P", strtotime($signature->signingTime));
            }
            if (isset($signature->certifiedDateReference)) {
                $signature->certifiedDateReference = date("d/m/Y H:i:s P",
                    strtotime($signature->certifiedDateReference));
            }

            if (isset($signature->timestamps)) {
                foreach ($signature->timestamps as $timestamp) {
                    if (isset($timestamp->messageImprint)) {
                        $timestamp->messageImprint->algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($timestamp->messageImprint->algorithm);
                        $timestamp->messageImprint->value = base64_decode($timestamp->messageImprint->value);
                    }
                }
            }

            if (isset($signature->certificate)) {
                if (isset($signature->certificate->pkiBrazil)) {

                    if (isset($signature->certificate->pkiBrazil->cpf)) {
                        $cpf = $signature->certificate->pkiBrazil->cpf;
                        $signature->certificate->pkiBrazil->cpfFormatted = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3)
                            . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
                    } else {
                        $signature->certificate->pkiBrazil->cpfFormatted = '';
                    }

                    if (isset($signature->certificate->pkiBrazil->cnpj)) {
                        $cnpj = $signature->certificate->pkiBrazil->cnpj;
                        $signature->certificate->pkiBrazil->cnpjFormatted = substr($cnpj, 0, 2) . '.'
                            . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-'
                            . substr($cnpj, 12);
                    } else {
                        $signature->certificate->pkiBrazil->cnpjFormatted = '';
                    }
                }
            }
        }

        return $response;
    }
}