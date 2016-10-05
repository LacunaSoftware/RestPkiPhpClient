<?php

namespace Lacuna\RestPki\Client;

class CadesSignatureStarter extends SignatureStarter
{

    private $contentToSign;
    private $cmsToCoSign;
    private $encapsulateContent;

    public function __construct($restPkiClient)
    {
        parent::__construct($restPkiClient);
    }

    public function setFileToSign($filePath)
    {
        $this->contentToSign = file_get_contents($filePath);
    }

    public function setContentToSign($content)
    {
        $this->contentToSign = $content;
    }

    public function setCmsFileToSign($cmsPath)
    {
        $this->cmsToCoSign = file_get_contents($cmsPath);
    }

    public function setCmsToSign($cmsBytes)
    {
        $this->cmsToCoSign = $cmsBytes;
    }

    public function setEncapsulateContent($encapsulateContent)
    {
        $this->encapsulateContent = $encapsulateContent;
    }

    public function startWithWebPki()
    {

        if (empty($this->contentToSign) && empty($this->cmsToCoSign)) {
            throw new \Exception("The content to sign was not set and no CMS to be co-signed was given");
        }
        if (!isset($this->signaturePolicyId)) {
            throw new \Exception("The signature policy was not set");
        }

        $request = array(
            'certificate' => $this->signerCertificate, // may be null
            'signaturePolicyId' => $this->signaturePolicyId,
            'securityContextId' => $this->securityContextId,
            'callbackArgument' => $this->callbackArgument,
            'encapsulateContent' => $this->encapsulateContent
        );
        if (!empty($this->contentToSign)) {
            $request['contentToSign'] = base64_encode($this->contentToSign);
        }
        if (!empty($this->cmsToCoSign)) {
            $request['cmsToCoSign'] = base64_encode($this->cmsToCoSign);
        }

        $response = $this->restPkiClient->post('Api/CadesSignatures', $request);

        if (isset($response->certificate)) {
            $this->certificateInfo = $response->certicate;
        }
        $this->done = true;

        return $response->token;
    }

}