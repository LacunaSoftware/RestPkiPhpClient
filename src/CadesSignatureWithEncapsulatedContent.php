<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignatureWithEncapsulatedContent
 * @package Lacuna\RestPki
 */
class CadesSignatureWithEncapsulatedContent {

    public $signature;

    /** @var FileResult */
    public $encapsulatedContent;

    /**
     * @internal
     *
     * @param $signature
     * @param $encapsulatedContent FileResult
     */
    public function __construct($signature, $encapsulatedContent)
    {
        $this->signature = $signature;
        $this->encapsulatedContent = $encapsulatedContent;
    }
}
