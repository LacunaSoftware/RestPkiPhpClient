<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignature
 * @package Lacuna\RestPki
 *
 * @property-read $signers PadesSignerInfo[]
 */
class PadesSignature
{
    private $_signers = [];


    public function __construct($model)
    {
        foreach ($model->signers as $signer) {
            $this->_signers[] = new PadesSignerInfo($signer);
        }
    }

    /**
     * Gets the array of PAdES signer info instances.
     *
     * @return PadesSignerInfo[] The array of PAdES signer info instances.
     */
    public function getSigners()
    {
        return $this->_signers;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "signers":
                return $this->getSigners();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }

    public function __isset($attr)
    {
        switch ($attr) {
            case "signers":
                return isset($this->_signers);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}