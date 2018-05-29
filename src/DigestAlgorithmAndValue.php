<?php

namespace Lacuna\RestPki;

/**
 * Class DigestAlgorithmAndValue
 * @package Lacuna\RestPki
 *
 * @property-read $algorithm DigestAlgorithm
 * @property-read $value binary
 * @property-read $hexValue string
 */
class DigestAlgorithmAndValue
{
    private $_algorithm;
    private $_value;
    private $_hexValue;

    public function __construct($model)
    {
        $this->_algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($model->algorithm);
        $this->_value = base64_decode($model->value);
        $this->_hexValue = bin2hex($this->_value);
    }

    /**
     * Gets the digest algorithm.
     *
     * @return DigestAlgorithm The digest algorithm.
     */
    public function getAlgorithm()
    {
        return $this->_algorithm;
    }

    /**
     * Gets the digest algorithm's value.
     *
     * @return binary The digest algorithm's value.
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Gets the digest algorithm's hex value.
     *
     * @return string The digest algorithm's hex value.
     */
    public function getHexValue()
    {
        return $this->_hexValue;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "algorithm":
                return $this->getAlgorithm();
            case "value":
                return $this->getValue();
            case 'hexValue':
                return $this->getHexValue();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }

    public function __isset($attr)
    {
        switch ($attr) {
            case "algorithm":
                return isset($this->_algorithm);
            case "value":
                return isset($this->_value);
            case 'hexValue':
                return isset($this->_hexValue);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}