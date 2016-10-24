<?php

namespace Lacuna\RestPki\Client;

class PadesSignatureStarter extends SignatureStarter
{

    private $pdfContent;
    public $measurementUnits;
    public $pageOptimization;
    public $bypassMarksIfSigned;
    public $visualRepresentation;
    public $pdfMarks;

    public function __construct($restPkiClient)
    {
        parent::__construct($restPkiClient);
        $this->bypassMarksIfSigned = true;
        $this->done = false;
        $this->pdfMarks = [];
        $this->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
    }

    public function setPdfToSignPath($pdfPath)
    {
        $this->pdfContent = file_get_contents($pdfPath);
    }

    public function setPdfToSignContent($content)
    {
        $this->pdfContent = $content;
    }

    public function setVisualRepresentation($visualRepresentation)
    {
        $this->visualRepresentation = $visualRepresentation;
    }

    public function startWithWebPki()
    {

        if (empty($this->pdfContent)) {
            throw new \Exception("The PDF to sign was not set");
        }
        if (!isset($this->signaturePolicyId)) {
            throw new \Exception("The signature policy was not set");
        }

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicyId,
            'securityContextId' => $this->securityContextId,
            'callbackArgument' => $this->callbackArgument,
            'pdfMarks' => $this->pdfMarks,
            'bypassMarksIfSigned' => $this->bypassMarksIfSigned,
            'measurementUnits' => $this->measurementUnits,
            'pageOptimization' => $this->pageOptimization,
            'visualRepresentation' => $this->visualRepresentation
        );
        if (!empty($this->pdfContent)) {
            $request['pdfToSign'] = base64_encode($this->pdfContent);
        }

        $response = $this->restPkiClient->post('Api/PadesSignatures', $request);

        if (isset($response->certificate)) {
            $this->certificateInfo = $response->certificate;
        }
        $this->done = true;

        return $response->token;
    }

    public function start()
    {
        if (empty($this->pdfContent)) {
            throw new \Exception("The PDF to sign was not set");
        }
        if (empty($this->signerCertificateBase64)) {
            throw new \Exception("The signer certificate was not set");
        }
        if (empty($this->signaturePolicyId)) {
            throw new \Exception("The signature policy was not set");
        }

        $request = array(
            'certificate' => $this->signerCertificateBase64,
            'signaturePolicyId' => $this->signaturePolicyId,
            'securityContextId' => $this->securityContextId,
            'callbackArgument' => $this->callbackArgument,
            'pdfMarks' => $this->pdfMarks,
            'bypassMarksIfSigned' => $this->bypassMarksIfSigned,
            'measurementUnits' => $this->measurementUnits,
            'pageOptimization' => $this->pageOptimization,
            'visualRepresentation' => $this->visualRepresentation
        );
        if (!empty($this->pdfContent)) {
            $request['pdfToSign'] = base64_encode($this->pdfContent);
        }

        $response = $this->restPkiClient->post('Api/PadesSignatures', $request);

        if (isset($response->certificate)) {
            $this->certificateInfo = $response->certificate;
        }
        $this->done = true;

        return self::getClientSideInstructionsObject($response);
    }
}