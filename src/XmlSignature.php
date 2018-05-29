<?php

namespace Lacuna\RestPki;

/**
 * Class XmlSignature
 * @package Lacuna\RestPki
 *
 * @property-read $type string
 * @property-read $signedElement XmlElementInfo
 * @property-read $signaturePolicy SignaturePolicyIdentifier
 * @property-read $certificate PKCertificate
 * @property-read $signingTime string
 * @property-read $certifiedDateReference mixed
 * @property-read $timestamps CadesTimestamp[]
 * @property-read $validationResults ValidationResults
 */
class XmlSignature
{
    private $_type;
    private $_signedElement;
    private $_signaturePolicy;
    private $_certificate;
    private $_signingTime;
    private $_certifiedDateReference;
    private $_timestamps = [];
    private $_validationResults;

    public function __construct($model)
    {
        if (isset($model->signedElement)) {
            $this->_signedElement = new XmlElementInfo($model->signedElement);
        }
        $this->_type = $model->type;
        if (isset($model->signaturePolicy)) {
            $this->_signaturePolicy = new SignaturePolicyIdentifier($model->signaturePolicy);
        }
        $this->_certificate = new PKCertificate($model->certificate);
        $this->_signingTime = $model->signingTime;
        $this->_certifiedDateReference = $model->certifiedDateReference;
        if (isset($model->timestamps)) {
            foreach ($model->timestamps as $timestampModel) {
                $this->_timestamps[] = new CadesTimestamp($timestampModel);
            }
        }
        if (isset($model->validationResults)) {
            $this->_validationResults = new ValidationResults($model->validationResults);
        }

    }

    /**
     * Gets the signature type.
     *
     * @return string The signature type.
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Gets the signed element.
     *
     * @return XmlElementInfo The signed element.
     */
    public function getSignedElement()
    {
        return $this->_signedElement;
    }

    /**
     * Gets the signature policy.
     *
     * @return SignaturePolicyIdentifier The signature policy.
     */
    public function getSignaturePolicy()
    {
        return $this->_signaturePolicy;
    }

    /**
     * Gets the certificate.
     *
     * @return PKCertificate The certificate.
     */
    public function getCertificate()
    {
        return $this->_certificate;
    }

    /**
     * Gets the signing time.
     *
     * @return string The signing time.
     */
    public function getSigningTime()
    {
        return $this->_signingTime;
    }

    /**
     * Gets the certified date reference.
     *
     * @return mixed The certified date reference.
     */
    public function getCertifiedDateReference()
    {
        return $this->_certifiedDateReference;
    }

    /**
     * Gets array of CAdES timestamps.
     *
     * @return CadesTimestamp[] The array of CAdES timestamps.
     */
    public function getTimestamps()
    {
        return $this->_timestamps;
    }

    /**
     * Gets the validation results.
     *
     * @return ValidationResults The validation results.
     */
    public function getValidationResults()
    {
        return $this->_validationResults;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "type":
                return $this->getType();
            case "signedElement":
                return $this->getSignedElement();
            case "signaturePolicy":
                return $this->getSignaturePolicy();
            case "certificate":
                return $this->getCertificate();
            case "signingTime":
                return $this->getSigningTime();
            case "certifiedDateReference":
                return $this->getCertifiedDateReference();
            case "timestamps":
                return $this->getTimestamps();
            case "validationResults":
                return $this->getValidationResults();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }

    public function __isset($attr)
    {
        switch ($attr) {
            case "type":
                return isset($this->_type);
            case "signedElement":
                return isset($this->_signedElement);
            case "signaturePolicy":
                return isset($this->_signaturePolicy);
            case "certificate":
                return isset($this->_certificate);
            case "signingTime":
                return isset($this->_signingTime);
            case "certifiedDateReference":
                return isset($this->_certifiedDateReference);
            case "timestamps":
                return isset($this->_timestamps);
            case "validationResults":
                return isset($this->_validationResults);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}