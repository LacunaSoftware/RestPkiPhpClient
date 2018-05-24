<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignatureExplorer
 * @package Lacuna\RestPki
 */
class CadesSignatureExplorer extends SignatureExplorer
{
    const CMS_SIGNATURE_MIME_TYPE = "application/pkcs7-signature";

    /** @var FileReference */
    private $dataFile;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    #region setDataFile

    /**
     * Sets the detached data file's local path
     *
     * @param $path string The path of the detached data file
     */
    public function setDataFileFromPath($path)
    {
        $this->dataFile = FileReference::fromFile($path);
    }

    /**
     * Sets the detached data file's raw (binary) contents
     *
     * @param $contentRaw string The raw (binary) contents of the detached data file
     */
    public function setDataFileFromContentRaw($contentRaw)
    {
        $this->dataFile = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * Sets the detached data file's base64-encoded contents
     *
     * @param $contentBase64 string The base64-encoded contents of the detached data file
     */
    public function setDataFileFromContentBase64($contentBase64)
    {
        $this->dataFile = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * Alias of the function setDataFileFromPath
     *
     * @param $path string The path of the detached data file
     */
    public function setDataFile($path)
    {
        $this->setDataFileFromPath($path);
    }

    #endregion

    /**
     * @return CadesSignature The signature information
     */
    public function open()
    {
        return $this->openCommon(false);
    }

    /**
     * @return CadesSignatureWithEncapsulatedContent The signature information along with the extracted encapsulated content
     */
    public function openAndExtractContent()
    {
        $response = $this->openCommon(true);
        return new CadesSignatureWithEncapsulatedContent($response,
            new FileResult($this->client, $response->encapsulatedContent));
    }

    protected function openCommon($extractEncapsulatedContent)
    {

        $request = parent::getRequest();
        $request['extractEncapsulatedContent'] = $extractEncapsulatedContent;

        if (isset($this->dataFile)) {
            $requiredHashes = $this->getRequiredHashes();
            if (count($requiredHashes) > 0) {
                $request['dataHashes'] = $this->dataFile->computeDataHashes($requiredHashes);
            }
        }

        $response = $this->client->post("Api/CadesSignatures/Open", $request);

        return new CadesSignature($response);
    }

    private function getRequiredHashes()
    {
        $request = $this->signatureFile->uploadOrReference($this->client);
        $response = $this->client->post("Api/CadesSignatures/RequiredHashes", $request);
        $algs = array();
        foreach ($response as $alg) {
            array_push($algs, DigestAlgorithm::getInstanceByApiAlgorithm($alg));
        }
        return $algs;
    }
}
