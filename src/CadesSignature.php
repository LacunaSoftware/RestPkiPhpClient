<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignature
 * @package Lacuna\RestPki
 *
 * @property-read $signers CadesSignerInfo[]
 */
class CadesSignature
{
    private $_signers = [];

    public function __construct($model)
    {
        foreach ($model->signers as $signerModel) {
            $this->_signers[] = new CadesSignerInfo($signerModel);
        }
    }

    /**
     * Gets the array of CadesSignerInfo classes.
     *
     * @return CadesSignerInfo[] The array of CAdES signer info classes.
     */
    public function getSigners()
    {
        return $this->_signers;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case "signers":
                return $this->getSigners();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
                return null;
        }
    }
}