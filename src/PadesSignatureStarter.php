<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignatureStarter
 * @package Lacuna\RestPki
 *
 * @property $measurementUnits string
 * @property $pageOptimization string
 * @property $bypassMarksIfSigned bool|null
 * @property $visualRepresentation
 * @property $pdfMarks
 */
class PadesSignatureStarter extends SignatureStarter
{

    public $measurementUnits;
    public $pageOptimization;
    public $bypassMarksIfSigned;
    public $visualRepresentation;
    public $pdfMarks;

    /** @var FileReference */
    private $pdfToSign;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        parent::__construct($client);
        $this->bypassMarksIfSigned = true;
        $this->done = false;
        $this->pdfMarks = [];
        $this->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
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
     * @param $content string The binary contents of the PDF file to be signed
     */
    public function setPdfToSignFromBinary($content)
    {
        $this->pdfToSign = FileReference::fromBinary($content);
    }

    /**
     * @param $fileResult FileResult The result of a previous PAdES signature on Rest PKI
     */
    public function setPdfToSignFromResult($fileResult)
    {
        $this->pdfToSign = FileReference::fromResult($fileResult);
    }

    /**
     * @deprecated Use function setPdfToSignFromPath
     *
     * @param $path string The path of the PDF file to be signed
     */
    public function setPdfToSignPath($path)
    {
        $this->setPdfToSignFromPath($path);
    }

    /**
     * @deprecated
     *
     * @param $content string The binary contents of the PDF file to be signed
     */
    public function setPdfToSignContent($content)
    {
        $this->setPdfToSignFromBinary($content);
    }

    #endregion

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
            'visualRepresentation' => $this->visualRepresentation
        );

        $request['pdfToSign'] = $this->pdfToSign->uploadOrReference($this->client);

        return $this->client->post('Api/v2/PadesSignatures', $request);
    }
}
