<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignatureStarter
 * @package Lacuna\RestPki
 *
 * @property $measurementUnits string
 * @property $pageOptimization string
 * @property $bypassMarksIfSigned bool
 * @property $visualRepresentation
 * @property $pdfMarks
 * @property $customSignatureFieldName string
 * @property $certificationLevel
 * @property $reason string
 */
class PadesSignatureStarter extends SignatureStarter
{

    public $measurementUnits = PadesMeasurementUnits::CENTIMETERS;
    public $pageOptimization;
    public $bypassMarksIfSigned = true;
    public $visualRepresentation;
    public $pdfMarks = [];
    public $customSignatureFieldName;
    public $certificationLevel;
    public $reason;

    /** @var FileReference */
    private $pdfToSign;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
    }

    #region setPdfToSign

    /**
     * @param $path string The path of the PDF file to be signed
     */
    public function setPdfToSignFromPath($path)
    {
        $this->pdfToSign = FileReference::fromFile($path);
    }

    /**
     * Sets the raw (binary) contents of the PDF file to be signed
     *
     * @param $contentRaw string The raw (binary) contents of the PDF file to be signed
     */
    public function setPdfToSignFromContentRaw($contentRaw)
    {
        $this->pdfToSign = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * Sets the base64-encoded contents of the PDF file to be signed
     *
     * @param $contentBase64 string The base64-encoded contents of the PDF file to be signed
     */
    public function setPdfToSignFromContentBase64($contentBase64)
    {
        $this->pdfToSign = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * @param $fileResult FileResult The result of a previous PAdES signature on Rest PKI
     */
    public function setPdfToSignFromResult($fileResult)
    {
        $this->pdfToSign = FileReference::fromResult($fileResult);
    }

    /**
     * Alias of function setPdfToSignFromPath
     *
     * @param $path string The path of the PDF file to be signed
     */
    public function setPdfToSignPath($path)
    {
        $this->setPdfToSignFromPath($path);
    }

    /**
     * Alias of function setPdfToSignFromContentRaw
     *
     * @param $contentRaw string The raw (binary) contents of the PDF file to be signed
     */
    public function setPdfToSignContent($contentRaw)
    {
        $this->setPdfToSignFromContentRaw($contentRaw);
    }

    #endregion

    /**
     * @deprecated Use "encapsulatedContent" property.
     *
     * Alias of setting the property `visualRepresentation`
     *
     * @param $visualRepresentation
     */
    public function setVisualRepresentation($visualRepresentation)
    {
        $this->visualRepresentation = $visualRepresentation;
    }

    /**
     * @return string
     */
    public function startWithWebPki()
    {
        $response = $this->startCommon();

        if (isset($response->certificate)) {
            $this->_certificateInfo = $response->certificate;
        }
        $this->done = true;

        return $response->token;
    }

    /**
     * @return SignatureAlgorithmParameters
     */
    public function start()
    {
        if (empty($this->signerCertificateBase64)) {
            throw new \LogicException("The signer certificate was not set");
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
        if (empty($this->pdfToSign)) {
            throw new \LogicException("The PDF to sign was not set");
        }
        if (empty($this->signaturePolicy)) {
            throw new \LogicException("The signature policy was not set");
        }

        $apiVersion = $this->client->_getApiVersion('StartPades');

        switch ($apiVersion) {
            case 1:
                return $this->startCommonV1();
            default:
                return $this->startCommonV2();
        }
    }

    private function startCommonV1() {

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicy,
            'securityContextId' => $this->securityContext,
            'callbackArgument' => $this->callbackArgument,
            'pdfMarks' => $this->pdfMarks,
            'bypassMarksIfSigned' => $this->bypassMarksIfSigned,
            'measurementUnits' => $this->measurementUnits,
            'pageOptimization' => $this->pageOptimization,
            'visualRepresentation' => $this->visualRepresentation,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown,
            'customSignatureFieldName' => $this->customSignatureFieldName,
            'certificationLevel' => $this->certificationLevel,
            'reason' => $this->reason,
        );

        $request['pdfToSign'] = $this->pdfToSign->getContentBase64();

        return $this->client->post('Api/PadesSignatures', $request);
    }

    private function startCommonV2() {

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicy,
            'securityContextId' => $this->securityContext,
            'callbackArgument' => $this->callbackArgument,
            'pdfMarks' => $this->pdfMarks,
            'bypassMarksIfSigned' => $this->bypassMarksIfSigned,
            'measurementUnits' => $this->measurementUnits,
            'pageOptimization' => $this->pageOptimization,
            'visualRepresentation' => $this->visualRepresentation,
            'ignoreRevocationStatusUnknown' => $this->ignoreRevocationStatusUnknown,
            'customSignatureFieldName' => $this->customSignatureFieldName,
            'certificationLevel' => $this->certificationLevel,
            'reason' => $this->reason,
        );

        $request['pdfToSign'] = $this->pdfToSign->uploadOrReference($this->client);

        return $this->client->post('Api/v2/PadesSignatures', $request);
    }
}
