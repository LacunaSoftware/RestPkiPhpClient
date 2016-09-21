<?php

namespace Lacuna\RestPki\Client;

abstract class XmlSignatureStarter extends SignatureStarter
{

    protected $restPkiClient;
    protected $xmlContent;
    protected $signatureElementId;
    protected $signatureElementLocationXPath;
    protected $signatureElementLocationNsm;
    protected $signatureElementLocationInsertionOption;

    protected function __construct($restPkiClient)
    {
        parent::__construct($restPkiClient);
    }

    public function setXmlToSignPath($xmlPath)
    {
        $this->xmlContent = file_get_contents($xmlPath);
    }

    public function setXmlToSignContent($content)
    {
        $this->xmlContent = $content;
    }

    public function setSignatureElementLocation($xpath, $insertionOption, $namespaceManager = null)
    {
        $this->signatureElementLocationXPath = $xpath;
        $this->signatureElementLocationInsertionOption = $insertionOption;
        $this->signatureElementLocationNsm = $namespaceManager;
    }

    public function setSignatureElementId($signatureElementId)
    {
        $this->signatureElementId = $signatureElementId;
    }

    protected function verifyCommonParameters($isWithWebPki = false)
    {
        if (!$isWithWebPki) {
            if (empty($this->signerCertificate)) {
                throw new \Exception('The certificate was not set');
            }
        }
        if (empty($this->signaturePolicyId)) {
            throw new \Exception('The signature policy was not set');
        }
    }

    protected function getRequest()
    {
        $request = array(
            'signaturePolicyId' => $this->signaturePolicyId,
            'securityContextId' => $this->securityContextId,
            'signatureElementId' => $this->signatureElementId
        );
        if ($this->xmlContent != null) {
            $request['xml'] = base64_encode($this->xmlContent);
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