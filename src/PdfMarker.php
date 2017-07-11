<?php

namespace Lacuna\RestPki;


class PdfMarker
{

    public $measurementUnits;
    public $pageOptimization;
    public $abortIfSigned;
    public $marks;
    public $forceBlobResult;

    /** @var  RestPkiClient */
    private $client;

    /** @var FileReference */
    private $file;

    /**
     * @param RestPkiClient $client
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->marks = [];
        $this->measurementUnits = PadesMeasurementUnits::CENTIMETERS;
    }

    /**
     * Sets the path of the PDF file
     *
     * @param $path string the path of the PDF file
     */
    public function setFileFromPath($path)
    {
        $this->file = FileReference::fromFile($path);
    }

    /**
     * Sets the raw (binary) contents of the PDF file
     *
     * @param $contentRaw string The raw (binary) contents of the PDF file
     */
    public function setFileFromContentRaw($contentRaw)
    {
        $this->file = FileReference::fromContentRaw($contentRaw);
    }

    /**
     * Sets the base64-encoded of the PDF file
     *
     * @param $contentBase64 string The base64-encoded of the PDF file
     */
    public function setFileFromContentBase64($contentBase64)
    {
        $this->file = FileReference::fromContentBase64($contentBase64);
    }

    /**
     * Sets the PDF from a FileResult form a previous call to Rest PKI
     *
     * @param $fileResult FileResult The result of the a previous PAdES signature on Rest PKI
     */
    public function setFileFromResult($fileResult)
    {
        $this->file = FileReference::fromResult($fileResult);
    }

    /**
     * Sets the blob reference of the file
     *
     * @param $fileBlob string The blob reference with one of the RestPkiClient.uploadFile methods
     */
    public function setFileFromBlob($fileBlob)
    {
        $this->file = FileReference::fromBlob($fileBlob);
    }

    public function apply()
    {
        $apiVersion = $this->client->_getApiVersion('AddPdfMarks');
        if ($apiVersion < 1) {
            throw new \RuntimeException('The PdfMarker class can only be used with Rest PKI 1.13 or later. Please contact technical support to update your Rest PKI.');
        }

        $request = array(
            'marks' => $this->marks,
            'measurementUnits' => $this->measurementUnits,
            'pageOptimization' => $this->pageOptimization,
            'forceBlobResult' => $this->forceBlobResult,
            'abortIfSigned' => $this->abortIfSigned
        );
        $request['file'] = $this->file->uploadOrReference($this->client);
        $response = $this->client->post('Api/Pdf/AddMarks', $request);
        return new FileResult($this->client, $response->file);
    }
}