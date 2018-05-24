<?php

namespace Lacuna\RestPki;

/**
 * Class SignaturePolicyIdentifier
 * @package Lacuna\RestPki
 *
 * @property-read $digest DigestAlgorithmAndValue
 * @property-read $oid string
 * @property-read $uri string
 */
class SignaturePolicyIdentifier
{
    private $_digest;
    private $_oid;
    private $_uri;

    public function __construct($model)
    {
        $this->_digest = new DigestAlgorithmAndValue($model->digest);
        $this->_oid = $model->oid;
        $this->_uri = $model->uri;
    }

    /**
     * Gets the digest algorithm instance referred to this signature policy.
     *
     * @return DigestAlgorithmAndValue The digest algorithm instance.
     */
    public function getDigest()
    {
        return $this->_digest;
    }

    /**
     * Gets the OID of this signature policy.
     *
     * @return string The OID of this signature policy.
     */
    public function getOid()
    {
        return $this->_oid;
    }

    /**
     * gets the uri of this signature policy.
     *
     * @return string the URI of this signature policy.
     */
    public function getUri()
    {
        return $this->_uri;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "digest":
                return $this->getDigest();
            case "oid":
                return $this->getOid();
            case "uri":
                return $this->getUri();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}