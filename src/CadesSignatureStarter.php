<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignatureStarter
 * @package Lacuna\RestPki
 *
 * @property bool|null $encapsulateContent
 * @property DigestAlgorithm[] $digestAlgorithmsForDetachedSignature
 */
class CadesSignatureStarter extends SignatureStarter
{
    public $encapsulateContent;

    public $digestAlgorithmsForDetachedSignature;

    /** @var FileReference */
    private $fileToSign;

    /** @var FileReference */
    private $cmsToCoSign;

    /**
     * @param $client RestPkiClient
     */
    public function __construct($client)
    {
        parent::__construct($client);
        $this->digestAlgorithmsForDetachedSignature = array(DigestAlgorithm::getSHA1(), DigestAlgorithm::getSHA256());
    }

    #region setFileToSign

    /**
     * Sets the local path of the file to be signed
     *
     * @param $path string The path of the file to be signed
     */
    public function setFileToSignFromPath($path)
    {
        $this->fileToSign = FileReference::fromFile($path);
    }

    /**
     * Sets the raw (binary) contents of the file to be signed
     *
     * @param $contentRaw string The binary contents of the file to be signed
     */
    public function setFileToSignFromContentRaw($contentRaw)
    {
        $this->fileToSign = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * Sets the base64-encoded contents of the file to be signed
     *
     * @param $contentBase64 string The base64-encoded contents of the file to be signed
     */
    public function setFileToSignFromContentBase64($contentBase64)
    {
        $this->fileToSign = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * Sets the file to be signed from a previous operation on Rest PKI
     *
     * @param $fileResult FileResult The result of a previous operation on Rest PKI
     */
    public function setFileToSignFromResult($fileResult)
    {
        $this->fileToSign = FileReference::fromResult($fileResult);
    }

    /**
     * Alias of function setFileToSignFromPath
     *
     * @param $path string The path of the file to be signed
     */
    public function setFileToSign($path)
    {
        $this->setFileToSignFromPath($path);
    }

    /**
     * Alias of function setFileToSignFromContentRaw
     *
     * @param $contentRaw string The raw (binary) contents of the file to be signed
     */
    public function setContentToSign($contentRaw)
    {
        $this->setFileToSignFromContentRaw($contentRaw);
    }

    #endregion

    #region setCmsToCoSign

    /**
     * Sets the local path of the CMS (PKCS#7) file to be co-signed
     *
     * @param $path string The path of the CMS (PKCS#7) file to be co-signed
     */
    public function setCmsToCoSignFromPath($path)
    {
        $this->cmsToCoSign = FileReference::fromFile($path);
    }

    /**
     * Sets the raw (binary) contents of the CMS (PKCS#7) file to be co-signed
     *
     * @param $contentRaw string The raw (binary) contents of the CMS (PKCS#7) file to be co-signed
     */
    public function setCmsToCoSignFromContentRaw($contentRaw)
    {
        $this->cmsToCoSign = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * Sets the base64-encoded contents of the CMS (PKCS#7) file to be co-signed
     *
     * @param $contentBase64 string The base64-encoded contents of the CMS (PKCS#7) file to be co-signed
     */
    public function setCmsToCoSignFromContentBase64($contentBase64)
    {
        $this->cmsToCoSign = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * Sets the CMS (PKCS#7) file to be co-signed from a CAdES signature previously done on Rest PKI
     *
     * @param $fileResult FileResult The result of a previous CAdES signature on Rest PKI
     */
    public function setCmsToCoSignFromResult($fileResult)
    {
        $this->cmsToCoSign = FileReference::fromResult($fileResult);
    }

    /**
     * Alias of function setCmsToCoSignFromPath
     *
     * @param $path string The path of the CMS (PKCS#7) file to be co-signed
     */
    public function setCmsFileToSign($path)
    {
        $this->setCmsToCoSignFromPath($path);
    }

    /**
     * Alias of function setCmsToCoSignFromContentRaw
     *
     * @param $contentRaw string The raw (binary) contents of the CMS (PKCS#7) file to be co-signed
     */
    public function setCmsToSign($contentRaw)
    {
        $this->setCmsToCoSignFromContentRaw($contentRaw);
    }

    #endregion

    /**
     * Alias of setting the property encapsulateContent
     *
     * @param $encapsulateContent
     */
    public function setEncapsulateContent($encapsulateContent)
    {
        $this->encapsulateContent = $encapsulateContent;
    }

    public function startWithWebPki()
    {
        $response = $this->startCommon();

        if (isset($response->certificate)) {
            $this->_certificateInfo = $response->certicate;
        }
        $this->done = true;

        return $response->token;
    }

    public function start()
    {
        if (empty($this->signerCertificateBase64)) {
            throw new \Exception("The signer certificate was not set");
        }

        $response = $this->startCommon();

        if (isset($response->certificate)) {
            $this->_certificateInfo = $response->certificate;
        }
        $this->done = true;

        return self::getClientSideInstructionsObject($response);
    }

    private function startCommon()
    {
        if (empty($this->fileToSign) && empty($this->cmsToCoSign)) {
            throw new \Exception("The content to sign was not set and no CMS to be co-signed was given");
        }
        if (empty($this->signaturePolicy)) {
            throw new \Exception("The signature policy was not set");
        }

        $apiVersion = $this->client->_getApiVersion('StartCades');

        switch ($apiVersion) {
            case 1:
                return $this->startCommonV1();
            case 2:
                return $this->startCommonV2();
            default:
                return $this->startCommonV3();
        }
    }

    private function startCommonV1() {

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicy,
            'securityContextId' => $this->securityContext,
            'callbackArgument' => $this->callbackArgument,
            'encapsulateContent' => $this->encapsulateContent,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown
        );

        if (isset($this->fileToSign)) {
            $request['contentToSign'] = $this->fileToSign->getContentBase64();
        }
        if (isset($this->cmsToCoSign)) {
            $request['cmsToCoSign'] = $this->cmsToCoSign->getContentBase64();
        }

        return $this->client->post('Api/CadesSignatures', $request);
    }

    private function startCommonV2() {

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicy,
            'securityContextId' => $this->securityContext,
            'callbackArgument' => $this->callbackArgument,
            'encapsulateContent' => $this->encapsulateContent,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown
        );

        if (isset($this->fileToSign)) {
            if ($this->encapsulateContent === false) {
                $request['dataHashes'] = $this->fileToSign->computeDataHashes($this->digestAlgorithmsForDetachedSignature);
            } else {
                $request['contentToSign'] = $this->fileToSign->getContentBase64();
            }
        }

        if (isset($this->cmsToCoSign)) {
            $request['cmsToCoSign'] = $this->cmsToCoSign->getContentBase64();
        }

        return $this->client->post('Api/v2/CadesSignatures', $request);
    }

    private function startCommonV3() {

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicy,
            'securityContextId' => $this->securityContext,
            'callbackArgument' => $this->callbackArgument,
            'encapsulateContent' => $this->encapsulateContent,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown
        );

        if (isset($this->fileToSign)) {
            if ($this->encapsulateContent === false) {
                $request['dataHashes'] = $this->fileToSign->computeDataHashes($this->digestAlgorithmsForDetachedSignature);
            } else {
                $request['fileToSign'] = $this->fileToSign->uploadOrReference($this->client);
            }
        }

        if (isset($this->cmsToCoSign)) {
            $request['cmsToCoSign'] = $this->cmsToCoSign->uploadOrReference($this->client);
        }

        return $this->client->post('Api/v3/CadesSignatures', $request);
    }
}
