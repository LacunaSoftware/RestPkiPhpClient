<?php

namespace Lacuna\RestPki;

/**
 * Class DigestAlgorithmAndValue
 * @package Lacuna\RestPki
 *
 * @property-read DigestAlgorithm $algorithm
 * @property-read binary $value
 * @property-read string $hexValue
 */
class DigestAlgorithmAndValue
{
    private $_algorithm;
    private $_value;
    private $_hexValue;

    public function __construct($model)
    {
        if (isset($model->algorithm)) {
            $this->_algorithm = DigestAlgorithm::getInstanceByApiAlgorithm($model->algorithm);
        } else if (isset($model->algorithmObj)) {
            $this->_algorithm = $model->algorithmObj;
        }

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

    /**
     * Gets instance.
     *
     * @param $algorithmObj DigestAlgorithm algorithm object.
     * @param $value binary digest value.
     * @return DigestAlgorithmAndValue
     */
    public function getInstance($algorithmObj, $value){
        $model = array(
            'algorithmObj' => $algorithmObj,
            'value' => $value
        );
        return new DigestAlgorithmAndValue((object)$model);
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

    public function toModel ()
    {
        return array(
            'algorithm' => $this->_algorithm->id,
            'hexValue' => $this->_hexValue
        );
    }
}