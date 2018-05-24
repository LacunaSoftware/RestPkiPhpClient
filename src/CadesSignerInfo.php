<?php

namespace Lacuna\RestPki;

/**
 * Class CadesSignerInfo
 * @package Lacuna\RestPki
 *
 * @property-read $messageDigest DigestAlgorithmAndValue
 * @property-read $signaturePolicy SignaturePolicyIdentifier
 * @property-read $certificate PKCertificate
 * @property-read $signingTime string
 * @property-read $certifiedDateReference mixed
 * @property-read $timestamps CadesTimestamp[]
 * @property-read $validationResults ValidationResults
 */
class CadesSignerInfo
{
    private $_messageDigest;
    private $_signaturePolicy;
    private $_certificate;
    private $_signingTime;
    private $_certifiedDateReference;
    private $_timestamps = [];
    private $_validationResults;

    public function __construct($model)
    {
        $this->_messageDigest = new DigestAlgorithmAndValue($model->messageDigest);
        $this->_certificate = new PKCertificate($model->certificate);
        $this->_signingTime = $model->signingTime;
        $this->_certifiedDateReference = $model->certifiedDateReference;
        if (isset($model->signaturePolicy)) {
            $this->_signaturePolicy = new SignaturePolicyIdentifier($model->signaturePolicy);
        }
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
     * Gets the message digest.
     *
     * @return DigestAlgorithmAndValue The message digest.
     */
    public function getMessageDigest()
    {
        return $this->_messageDigest;
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
            case "messageDigest":
                return $this->getMessageDigest();
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
}