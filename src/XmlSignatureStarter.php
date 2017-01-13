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
     * @param $content string The binary contents of the XML file to be signed
     */
    public function setXmlToSignFromBinary($content)
    {
        $this->xmlToSign = FileReference::fromBinary($content);
    }

    /**
     * @deprecated Use function setXmlToSignFromPath
     *
     * @param $path string The path of the XML file to be signed
     */
    public function setXmlToSignPath($path)
    {
        $this->setXmlToSignFromPath($path);
    }

    /**
     * @deprecated Use function setXmlToSignFromBinary
     *
     * @param $content string The binary contents of the XML file to be signed
     */
    public function setXmlToSignContent($content)
    {
        $this->setXmlToSignFromBinary($content);
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
            'signatureElementId' => $this->signatureElementId
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
