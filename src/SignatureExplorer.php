<?php

namespace Lacuna\RestPki\Client;

abstract class SignatureExplorer
{

    /** @var RestPkiClient */
    protected $restPkiClient;
    protected $signatureFileContent;
    protected $validate;
    protected $defaultSignaturePolicyId;
    protected $acceptableExplicitPolicies;
    protected $securityContextId;

    protected function __construct($restPkiClient)
    {
        $this->restPkiClient = $restPkiClient;
    }

    public function setSignatureFile($filePath)
    {
        $this->signatureFileContent = file_get_contents($filePath);
    }

    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    public function setDefaultSignaturePolicy($signaturePolicyId)
    {
        $this->defaultSignaturePolicyId = $signaturePolicyId;
    }

    public function setAcceptableExplicitPolicies($policyCatalog)
    {
        $this->acceptableExplicitPolicies = $policyCatalog;
    }

    public function setSecurityContext($securityContextId)
    {
        $this->securityContextId = $securityContextId;
    }

    protected function getRequest($mimeType)
    {
        $request = array(
            "validate" => $this->validate,
            "defaultSignaturePolicyId" => $this->defaultSignaturePolicyId,
            "securityContextId" => $this->securityContextId,
            "acceptableExplicitPolicies" => $this->acceptableExplicitPolicies
        );

        if (!empty($this->signatureFileContent)) {
            $request['file'] = array(
                "content" => base64_encode($this->signatureFileContent),
                "mimeType" => $mimeType
            );
        }

        return $request;
    }
}