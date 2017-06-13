<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignatureWithEncapsulatedContent
 * @package Lacuna\RestPki
 *
 * @property-read mixed $signature
 * @property-read FileResult $encapsulatedContent
 */
class CadesSignatureWithEncapsulatedContent
{

    private $_signature;

    /** @var FileResult */
    private $_encapsulatedContent;

    /**
     * @internal
     *
     * @param mixed $signature
     * @param FileResult $encapsulatedContent
     */
    public function __construct($signature, $encapsulatedContent)
    {
        $this->_signature = $signature;
        $this->_encapsulatedContent = $encapsulatedContent;
    }

    public function __get($name)
    {
        switch ($name) {
            case "signature":
                return $this->_signature;
            case "encapsulatedContent":
                return $this->_encapsulatedContent;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
