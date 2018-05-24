<?php

namespace Lacuna\RestPki;

/**
 * Class PKCertificate
 * @package Lacuna\RestPki
 *
 * @property-read $subjectName string
 * @property-read $emailAddress string
 * @property-read $issuerName string
 * @property-read $serialNumber string
 * @property-read $validityStart string
 * @property-read $validityEnd string
 * @property-read $pkiBrazil PkiBrazilCertificateFields
 * @property-read $pkiItaly PkiItalyCertificateFields
 * @property-read $issuer PKCertificate
 * @property-read $binaryThumbprintSHA256 string
 * @property-read $thumbprint string
 */
class PKCertificate
{
    public $_subjectName;
    public $_emailAddress;
    public $_issuerName;
    public $_serialNumber;
    public $_validityStart;
    public $_validityEnd;
    public $_pkiBrazil;
    public $_pkiItaly;
    public $_issuer;
    public $_binaryThumbprintSHA256;
    public $_thumbprint;


    public function __construct($model)
    {
        $this->_subjectName = $model->subjectName;
        $this->_emailAddress = $model->emailAddress;
        $this->_issuerName = $model->issuerName;
        $this->_serialNumber = $model->serialNumber;
        $this->_validityStart = $model->validityStart;
        $this->_validityEnd = $model->validityEnd;
        if ($model->pkiBrazil) {
            $this->_pkiBrazil = new PkiBrazilCertificateFields($model->pkiBrazil);
        }
        if ($model->pkiItaly) {
            $this->_pkiItaly = new PkiItalyCertificateFields($model->pkiItaly);
        }
        if ($model->issuer) {
            $this->_issuer = new PKCertificate($model->issuer);
        }
        $this->_binaryThumbprintSHA256 = base64_decode($model->binaryThumbprintSHA256);
        $this->_thumbprint = $model->thumbprint;
    }

    /**
     * Gets the certificate's subject name field.
     *
     * @return string The certificate's subject name field.
     */
    public function getSubjectName()
    {
        return $this->_subjectName;
    }

    /**
     * Gets the certificate's email address field.
     *
     * @return string The certificate's email address field.
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }

    /**
     * Gets the certificate's issuer name field.
     *
     * @return string The certificate's issuer name field.
     */
    public function getIssuerName()
    {
        return $this->_issuerName;
    }

    /**
     * Gets the certificate's serial number field.
     *
     * @return string The certificate's serial number field.
     */
    public function getSerialNumber()
    {
        return $this->_serialNumber;
    }

    /**
     * Gets the certificate's validity start field.
     *
     * @return string The certificate's validity start field.
     */
    public function getValidityStart()
    {
        return $this->_validityStart;
    }

    /**
     * Gets the certificate's validity end field.
     *
     * @return string The certificate's validity end field.
     */
    public function getValidityEnd()
    {
        return $this->_validityEnd;
    }

    /**
     * Gets the certificate's PKI Brazil fields field.
     *
     * @return PkiBrazilCertificateFields The certificate's PKI Brazil fields field.
     */
    public function getPkiBrazil()
    {
        return $this->_pkiBrazil;
    }

    /**
     * Gets the certificate's PKI Italy fields field.
     *
     * @return PkiItalyCertificateFields The certificate's PKI Italy fields field.
     */
    public function getPkiItaly()
    {
        return $this->_pkiItaly;
    }

    /**
     * Gets the certificate's issuer field.
     *
     * @return PKCertificate The certificate's issuer field.
     */
    public function getIssuer()
    {
        return $this->_issuer;
    }

    /**
     * Gets the thumbprint content field.
     *
     * @return string The thumbprint content field.
     */
    public function getBinaryThumbprintSHA256()
    {
        return $this->_binaryThumbprintSHA256;
    }

    /**
     * Gets the thumbprint Base64-encoded content field.
     *
     * @return string The thumbprint Base64-encoded content field.
     */
    public function getThumbprint()
    {
        return $this->_thumbprint;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "subjectName":
                return $this->getSubjectName();
            case "emailAddress":
                return $this->getEmailAddress();
            case "issuerName":
                return $this->getIssuerName();
            case "serialNumber":
                return $this->getSerialNumber();
            case "validityStart":
                return $this->getValidityStart();
            case "validityEnd":
                return $this->getValidityEnd();
            case "pkiBrazil":
                return $this->getPkiBrazil();
            case "pkiItaly":
                return $this->getPkiItaly();
            case "issuer":
                return $this->getIssuer();
            case "binaryThumbprintSHA256":
                return $this->getBinaryThumbprintSHA256();
            case "signatureFieldName":
                return $this->getThumbprint();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}