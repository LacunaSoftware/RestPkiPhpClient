<?php

namespace Lacuna\RestPki;


class PdfMarkQRCodeElement extends PdfMarkElement
{
    public $qrCodeData;
    public $drawQuietZones;

    public function __construct($relativeContainer, $qrCodeData)
    {
        parent::__construct(PdfMarkElementType::QRCODE, $relativeContainer);
        $this->qrCodeData = $qrCodeData;
    }
}