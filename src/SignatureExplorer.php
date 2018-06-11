<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureExplorer
 * @package Lacuna\RestPki
 *
 * @property bool $validate
 * @property string $defaultSignaturePolicy
 * @property string[] $acceptableExplicitPolicies
 * @property string $securityContext
 * @property $ignoreRevocationStatusUnknown bool
 */
abstract class SignatureExplorer
{
    public $validate = true;
    public $defaultSignaturePolicy;
    public $acceptableExplicitPolicies;
    public $securityContext;
    public $ignoreRevocationStatusUnknown = false;

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
     * Sets the raw (binary) contents of the signature file to be opened
     *
     * @param $contentRaw string The raw (binary) contents of the signature file to be opened
     */
    public function setSignatureFileFromContentRaw($contentRaw)
    {
        $this->signatureFile = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * Sets the base64-encoded contents of the signature file to be opened
     *
     * @param $contentBase64 string The base64-encoded contents of the signature file to be opened
     */
    public function setSignatureFileFromContentBase64($contentBase64)
    {
        $this->signatureFile = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * Sets the signature file to be opened from the result of a previous operation on Rest PKI
     *
     * @param $fileResult FileResult The result of a previous operation on Rest PKI
     */
    public function setSignatureFileFromResult($fileResult)
    {
        $this->signatureFile = FileReference::fromResult($fileResult);
    }

    /**
     * Sets the signature file to be opened from the blob reference
     *
     * @param $fileBlob string The blob reference of the signature file to be opened
     */
    public function setSignatureFileFromBlob($fileBlob)
    {
        $this->signatureFile = FileReference::fromBlob($fileBlob);
    }

    /**
     * Alias of function setSignatureFileFromPath
     *
     * @param $path string The path of the signature file to be opened
     */
    public function setSignatureFile($path)
    {
        $this->setSignatureFileFromPath($path);
    }

    #endregion

    /**
     * Alias of setting property `validate`
     *
     * @param bool $validate
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;
    }

    /**
     * Alias of setting property `defaultSignaturePolicy`
     *
     * @param string $signaturePolicyId
     */
    public function setDefaultSignaturePolicyId($signaturePolicyId)
    {
        $this->defaultSignaturePolicy = $signaturePolicyId;
    }

    /**
     * Alias of setting property `acceptableExplicitPolicies`
     *
     * @param string[] $policyCatalog
     */
    public function setAcceptableExplicitPolicies($policyCatalog)
    {
        $this->acceptableExplicitPolicies = $policyCatalog;
    }

    /**
     * Alias of setting property `securityContext`
     *
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
            'acceptableExplicitPolicies' => $this->acceptableExplicitPolicies,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown
        );

        $request['file'] = $this->signatureFile->uploadOrReference($this->client);

        return $request;
    }
}
