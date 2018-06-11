<?php

namespace Lacuna\RestPki;

/**
 * Class XmlSignatureStarter
 * @package Lacuna\RestPki
 *
 * @property $signatureElementId string
 */
abstract class XmlSignatureStarter extends SignatureStarter
{
    public $signatureElementId;

    /** @var FileReference */
    protected $xmlToSign;
    protected $signatureElementLocationXPath;
    protected $signatureElementLocationNsm;
    protected $signatureElementLocationInsertionOption;

    /**
     * @param RestPkiClient $client
     */
    protected function __construct($client)
    {
        parent::__construct($client);
    }

    #region setXmlToSign

    /**
     * @param $path string The path of the XML file to be signed
     */
    public function setXmlToSignFromPath($path)
    {
        $this->xmlToSign = FileReference::fromFile($path);
    }

    /**
     * @param $contentRaw string The raw (binary) contents of the XML file to be signed
     */
    public function setXmlToSignFromContentRaw($contentRaw)
    {
        $this->xmlToSign = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * @param $contentBase64 string The base64-encoded contents of the XML file to be signed
     */
    public function setXmlToSignFromContentBase64($contentBase64)
    {
        $this->xmlToSign = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * Alias of function setXmlToSignFromPath
     *
     * @param $path string The path of the XML file to be signed
     */
    public function setXmlToSignPath($path)
    {
        $this->setXmlToSignFromPath($path);
    }

    /**
     * Alias of function setXmlToSignFromContentRaw
     *
     * @param $content string The raw (binary) contents of the XML file to be signed
     */
    public function setXmlToSignContent($content)
    {
        $this->setXmlToSignFromContentRaw($content);
    }

    #endregion

    /**
     * @param string $xpath
     * @param string $insertionOption
     * @param $namespaceManager
     */
    public function setSignatureElementLocation($xpath, $insertionOption, $namespaceManager = null)
    {
        $this->signatureElementLocationXPath = $xpath;
        $this->signatureElementLocationInsertionOption = $insertionOption;
        $this->signatureElementLocationNsm = $namespaceManager;
    }

    /**
     * Alias of setting the property `signatureElementId`
     *
     * @param string $signatureElementId
     */
    public function setSignatureElementId($signatureElementId)
    {
        $this->signatureElementId = $signatureElementId;
    }

    protected function verifyCommonParameters($isWithWebPki = false)
    {
        if (!$isWithWebPki) {
            if (empty($this->signerCertificateBase64)) {
                throw new \LogicException("The signer certificate was not set");
            }
        }
        if (empty($this->signaturePolicy)) {
            throw new \LogicException('The signature policy was not set');
        }
    }

    protected function getRequest()
    {
        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicy,
            'securityContextId' => $this->securityContext,
            'signatureElementId' => $this->signatureElementId,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown
        );
        if (isset($this->xmlToSign)) {
            $request['xml'] = $this->xmlToSign->getContentBase64();
        }
        if ($this->signatureElementLocationXPath != null && $this->signatureElementLocationInsertionOption != null) {
            $request['signatureElementLocation'] = array(
                'xPath' => $this->signatureElementLocationXPath,
                'insertionOption' => $this->signatureElementLocationInsertionOption
            );
            if ($this->signatureElementLocationNsm != null) {
                $namespaces = array();
                foreach ($this->signatureElementLocationNsm as $key => $value) {
                    $namespaces[] = array(
                        'prefix' => $key,
                        'uri' => $value
                    );
                    $request['signatureElementLocation']['namespaces'] = $namespaces;
                }
            }
        }
        return $request;
    }
}
