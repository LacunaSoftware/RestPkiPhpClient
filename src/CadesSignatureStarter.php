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
     * @param $path string The path of the file to be signed
     */
    public function setFileToSignFromPath($path)
    {
        $this->fileToSign = FileReference::fromFile($path);
    }

    /**
     * @param $content string The binary contents of the file to be signed
     */
    public function setFileToSignFromBinary($content)
    {
        $this->fileToSign = FileReference::fromBinary($content);
    }

    /**
     * @param $fileResult FileResult The result of a previous operation on Rest PKI
     */
    public function setFileToSignFromResult($fileResult)
    {
        $this->fileToSign = FileReference::fromResult($fileResult);
    }

    /**
     * @deprecated Use function setFileToSignFromPath
     *
     * @param $path string The path of the file to be signed
     */
    public function setFileToSign($path)
    {
        $this->setFileToSignFromPath($path);
    }

    /**
     * @deprecated Use function setFileToSignFromBinary
     *
     * @param $content string The binary contents of the file to be signed
     */
    public function setContentToSign($content)
    {
        $this->setFileToSignFromBinary($content);
    }

    #endregion

    #region setCmsToCoSign

    /**
     * @param $path string The path of the CMS (PKCS #7) file to be co-signed
     */
    public function setCmsToCoSignFromPath($path)
    {
        $this->cmsToCoSign = FileReference::fromFile($path);
    }

    /**
     * @param $content string The binary contents of the CMS (PKCS #7) file to be co-signed
     */
    public function setCmsToCoSignFromBinary($content)
    {
        $this->cmsToCoSign = FileReference::fromBinary($content);
    }

    /**
     * @param $fileResult FileResult The result of a previous CAdES signature on Rest PKI
     */
    public function setCmsToCoSignFromResult($fileResult)
    {
        $this->cmsToCoSign = FileReference::fromResult($fileResult);
    }

    /**
     * @deprecated Use function setCmsToCoSignFromPath
     *
     * @param $path string The path of the CMS (PKCS #7) file to be co-signed
     */
    public function setCmsFileToSign($path)
    {
        $this->setCmsToCoSignFromPath($path);
    }

    /**
     * @deprecated Use function setCmsToCoSignFromBinary
     *
     * @param $content string The binary contents of the CMS (PKCS #7) file to be co-signed
     */
    public function setCmsToSign($content)
    {
        $this->setCmsToCoSignFromBinary($content);
    }

    #endregion

    /**
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
            'encapsulateContent' => $this->encapsulateContent
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
            'encapsulateContent' => $this->encapsulateContent
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
            'encapsulateContent' => $this->encapsulateContent
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
