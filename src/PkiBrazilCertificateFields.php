<?php

namespace Lacuna\RestPki;

/**
 * Class PkiBrazilCertificateFields
 * @package Lacuna\RestPki
 *
 * @property-read $certificateType string
 * @property-read $cpf string
 * @property-read $cpfFormatted string
 * @property-read $cnpj string
 * @property-read $cnpjFormatted string
 * @property-read $responsavel string
 * @property-read $dateOfBirth string
 * @property-read $companyName string
 * @property-read $rgNumero string
 * @property-read $rgEmissor string
 * @property-read $rgEmissorUF string
 * @property-read $oabNumero string
 * @property-read $oabUF string
 */
class PkiBrazilCertificateFields
{
    public $_certificateType;
    public $_cpf;
    public $_cpfFormatted;
    public $_cnpj;
    public $_cnpjFormatted;
    public $_responsavel;
    public $_dateOfBirth;
    public $_companyName;
    public $_rgNumero;
    public $_rgEmissor;
    public $_rgEmissorUF;
    public $_oabNumero;
    public $_oabUF;

    public function __construct($model)
    {
        $this->_certificateType = $model->certificateType;
        $this->_cpf = $model->cpf;
        $this->_cnpj = $model->cnpj;
        $this->_responsavel = $model->responsavel;
        $this->_dateOfBirth = $model->dateOfBirth;
        $this->_companyName = $model->companyName;
        $this->_rgNumero = $model->rgNumero;
        $this->_rgEmissor = $model->rgEmissor;
        $this->_rgEmissorUF = $model->rgEmissorUF;
        $this->_oabNumero = $model->oabNumero;
        $this->_oabUF = $model->oabUF;

        if (empty($model->cpf)) {
        $this->_cpfFormatted = "";
        } else {
            if (!preg_match("/^\d{11}$/", $model->cpf)) {
                $this->_cpfFormatted = $model->cpf;
            } else {
                $this->_cpfFormatted = sprintf("%s.%s.%s-%s", substr($model->cpf, 0, 3), substr($model->cpf, 3, 3),
                    substr($model->cpf, 6, 3), substr($model->cpf, 9));
            }
        }

        if (empty($model->cnpj)) {
            $this->_cnpjFormatted = "";
        } else {
            if (!preg_match("/^\d{14}$/", $model->cnpj)) {
                $this->_cnpjFormatted = $model->cnpj;
            } else {
                $this->_cnpjFormatted = sprintf("%s.%s.%s/%s-%s", substr($model->cpf, 0, 2), substr($model->cpf, 2, 3),
                    substr($model->cpf, 5, 3), substr($model->cpf, 8, 4), substr($model->cpf, 12));
            }
        }

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
     * Gets the certificate's "CPF" field.
     *
     * @return string the certificate's "CPF" field.
     */
    public function getCpf()
    {
        return $this->_cpf;
    }

    /**
     * Gets the certificate's formatted "CPF" field.
     *
     * @return string The certificate's formatted "CPF" field.
     */
    public function getCpfFormatted()
    {
        return $this->_cpfFormatted;
    }

    /**
     * Gets the certificate's "CNPJ" field.
     *
     * @return string The certificate's "CNPJ" field.
     */
    public function getCnpj()
    {
        return $this->_cnpj;
    }

    /**
     * Gets the certificate's formatted "CNPJ" field.
     *
     * @return string The certificate's formatted "CNPJ" field.
     */
    public function getCnpjFormatted()
    {
        return $this->_cnpjFormatted;
    }

    /**
     * Gets the certificate's "responsavel".
     *
     * @return string The certificate's "responsavel" field.
     */
    public function getResponsavel()
    {
        return $this->_responsavel;
    }

    /**
     * Gets the certificate's date of birth field..
     *
     * @return string The certificate's date of birth field.
     */
    public function getDateOfBirth()
    {
        return $this->_dateOfBirth;
    }

    /**
     * Gets the certificate's company name field.
     *
     * @return string The certificate's company name field.
     */
    public function getCompanyName()
    {
        return $this->_companyName;
    }

    /**
     * Gets the certificate's "numero do RG" field.
     *
     * @return string The certificate's "numero do RG" field.
     */
    public function getRgNumero()
    {
        return $this->_rgNumero;
    }

    /**
     * Gets the certificate's "emissor do RG" field.
     *
     * @return string The certificate's "emissor do RG" field.
     */
    public function getRgEmissor()
    {
        return $this->_rgEmissor;
    }

    /**
     * Gets the certificate's "UF do emissor do RG" field.
     *
     * @return string The certificate's "UF do emissor do RG" field.
     */
    public function getRgEmissorUF()
    {
        return $this->_rgEmissorUF;
    }

    /**
     * Gets the certificate's "numero da carteira da OAB" field.
     *
     * @return string The certificate's "numero da carteira da OAB" field.
     */
    public function getOabNumero()
    {
        return $this->_oabNumero;
    }

    /**
     * Gets the certificate's "UF da carteira da OAB" field.
     *
     * @return string The certificate's "UF da carteira da OAB" field.
     */
    public function getOabUF()
    {
        return $this->_oabUF;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "certificateType":
                return $this->getCertificateType();
            case "cpf":
                return $this->getCpf();
            case "cpfFormatted":
                return $this->getCpfFormatted();
            case "cnpj":
                return $this->getCnpj();
            case "cnpjFormatted":
                return $this->getCnpjFormatted();
            case "responsavel":
                return $this->getResponsavel();
            case "dateOfBirth":
                return $this->getDateOfBirth();
            case "companyName":
                return $this->getCompanyName();
            case "rgNumero":
                return $this->getRgNumero();
            case "rgEmissor":
                return $this->getRgEmissor();
            case "rgEmissorUF":
                return $this->getRgEmissorUF();
            case "oabNumero":
                return $this->getOabNumero();
            case "oabUF":
                return $this->getOabUF();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }

    public function __isset($attr)
    {
        switch ($attr) {
            case "certificateType":
                return isset($this->_certificateType);
            case "cpf":
                return isset($this->_cpf);
            case "cpfFormatted":
                return isset($this->_cpfFormatted);
            case "cnpj":
                return isset($this->_cnpj);
            case "cnpjFormatted":
                return isset($this->_cnpjFormatted);
            case "responsavel":
                return isset($this->_responsavel);
            case "dateOfBirth":
                return isset($this->_dateOfBirth);
            case "companyName":
                return isset($this->_companyName);
            case "rgNumero":
                return isset($this->_rgNumero);
            case "rgEmissor":
                return isset($this->_rgEmissor);
            case "rgEmissorUF":
                return isset($this->_rgEmissorUF);
            case "oabNumero":
                return isset($this->_oabNumero);
            case "oabUF":
                return isset($this->_oabUF);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}