<?php

namespace Lacuna\RestPki\Client;

class CadesSignatureExplorer extends SignatureExplorer
{
    const CMS_SIGNATURE_MIME_TYPE = "application/pkcs7-signature";

    private $dataFileContent;

    public function __construct($client)
    {
        parent::__construct($client);
    }

    public function setDataFile($filePath)
    {
        $this->dataFileContent = file_get_contents($filePath);
    }

    public function open()
    {
        $dataHashes = null;
        if (empty($this->signatureFileContent)) {
            throw new \RuntimeException("The signature file to open not set");
        }

        if ($this->dataFileContent != null) {
            $requiredHashes = $this->getRequiredHashes();
            if (count($requiredHashes) > 0) {
                $dataHashes = $this->computeDataHashes($this->dataFileContent, $requiredHashes);
            }
        }

        $request = $this->getRequest(self::CMS_SIGNATURE_MIME_TYPE);
        $request['dataHashes'] = $dataHashes;
        $response = $this->restPkiClient->post("Api/CadesSignatures/Open", $request);

        foreach ($response->signers as $signer) {
            $signer->validationResults = new ValidationResults($signer->validationResults);
            $signer->messageDigest->algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($signer->messageDigest->algorithm);

            if (isset($signer->signingTime)) {
                $signer->signingTime = date("d/m/Y H:i:s P", strtotime($signer->signingTime));
            }
        }

        return $response;
    }

    private function getRequiredHashes()
    {
        $request = array(
            "content" => base64_encode($this->signatureFileContent),
            "mimeType" => self::CMS_SIGNATURE_MIME_TYPE
        );

        $response = $this->restPkiClient->post("Api/CadesSignatures/RequiredHashes", $request);

        $algs = [];

        foreach ($response as $alg) {
            array_push($algs, DigestAlgorithm::getInstanceByApiAlgorithm($alg));
        }

        return $algs;
    }

    private function computeDataHashes($dataFileStream, $algorithms)
    {
        $dataHashes = [];
        foreach ($algorithms as $algorithm) {
            $digestValue = mhash($algorithm->getHashId(), $dataFileStream);
            $dataHash = array(
                'algorithm' => $algorithm->getAlgorithm(),
                'value' => base64_encode($digestValue)
            );
            array_push($dataHashes, $dataHash);
        }
        return $dataHashes;
    }
}