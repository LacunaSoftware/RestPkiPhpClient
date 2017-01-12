<?php

namespace Lacuna\RestPki\Client;

/**
 * Class SignatureExplorer
 * @package Lacuna\RestPki\Client
 *
 * @property bool $validate
 * @property string $defaultSignaturePolicy
 * @property string[] $acceptableExplicitPolicies
 * @property string $securityContext
 */
abstract class SignatureExplorer
{
    public $validate;
    public $defaultSignaturePolicy;
    public $acceptableExplicitPolicies;
    public $securityContext;

    /** @var RestPkiClient */
    protected $client;

    /** @var FileReference */
    protected $signatureFile;

    /**
     * @param RestPkiClient $client
     */
    protected function __construct($client)
    {
        $this->client = $client;
        $this->validate = true;
    }

    #region setSignatureFile

    /**
     * @param $path string The path of the signature file to be opened
     */
    public function setSignatureFileFromPath($path)
    {
        $this->signatureFile = FileReference::fromFile($path);
    }

    /**
     * @param $content string The binary contents of the signature file to be opened
     */
    public function setSignatureFileFromBinary($content)
    {
        $this->signatureFile = FileReference::fromBinary($content);
    }

    /**
     * @param $fileResult FileResult The result of a previous operation on Rest PKI
     */
    public function setSignatureFileFromResult($fileResult)
    {
        $this->signatureFile = FileReference::fromResult($fileResult);
    }

    /**
     * @deprecated Use function setSignatureFileFromPath
     *
     * @param $path string The path of the signature file to be opened
     */
    public function setSignatureFile($path)
    {
        $this->setSignatureFileFromPath($path);
    }

    #endregion

    /**
     * @param bool $validate
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    /**
     * @param string $signaturePolicyId
     */
    public function setDefaultSignaturePolicyId($signaturePolicyId)
    {
        $this->defaultSignaturePolicy = $signaturePolicyId;
    }

    /**
     * @param string[] $policyCatalog
     */
    public function setAcceptableExplicitPolicies($policyCatalog)
    {
        $this->acceptableExplicitPolicies = $policyCatalog;
    }

    /**
     * @param string $securityContext
     */
    public function setSecurityContextId($securityContext)
    {
        $this->securityContext = $securityContext;
    }

    protected function getRequest()
    {
        if (empty($this->signatureFile)) {
            throw new \RuntimeException("The signature file to open not set");
        }

        $request = array(
            'validate' => $this->validate,
            'defaultSignaturePolicyId' => $this->defaultSignaturePolicy,
            'securityContextId' => $this->securityContext,
            'acceptableExplicitPolicies' => $this->acceptableExplicitPolicies
        );

        $request['file'] = $this->signatureFile->uploadOrReference($this->client);

        return $request;
    }
}
