<?php

namespace Lacuna\RestPki;

/**
 * Class PkiItalyCertificateFields
 * @package Lacuna\RestPki
 *
 * @property-read $certificateType string
 * @property-read $codiceFiscale string
 * @property-read $idCarta string
 */
class PkiItalyCertificateFields
{
    private $_certificateType;
    private $_codiceFiscale;
    private $_idCarta;

    public function __construct($model)
    {
        $this->_certificateType = $model->certificateType;
        $this->_codiceFiscale = $model->codiceFiscale;
        $this->_idCarta = $model->idCarta;
    }

    /**
     * Gets the certificate's type field.
     *
     * @return string The certificate's type field.
     */
    public function getCertificateType()
    {
        return $this->_certificateType;
    }

    /**
     * Gets the certificate's "codice fiscale" field.
     * @return string The certificate's "codice fiscale" field.
     */
    public function getCodiceFiscale()
    {
        return $this->_codiceFiscale;
    }

    /**
     * Gets the certificate's "id carta" field.
     *
     * @return string The certificate's "Id carta" field.
     */
    public function getIdCarta()
    {
        return $this->_idCarta;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "certificateType":
                return $this->getCertificateType();
            case "codiceFiscale":
                return $this->getCodiceFiscale();
            case "idCarta":
                return $this->getIdCarta();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}