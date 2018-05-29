<?php

namespace Lacuna\RestPki;

/**
 * Class XmlAttributeInfo
 * @package Lacuna\RestPki
 *
 * @property-read $localName string
 * @property-read $namespaceUri mixed
 * @property-read $value mixed
 */
class XmlAttributeInfo
{
    private $_localName;
    private $_namespaceUri;
    private $_value;

    public function __construct($model)
    {
        $this->_localName = $model->localName;
        $this->_namespaceUri = $model->namespaceUri;
        $this->_value = $model->value;
    }

    /**
     * Gets the local name.
     *
     * @return string The local name.
     */
    public function getLocalName()
    {
        return $this->_localName;
    }

    /**
     * Gets the namespace URI.
     *
     * @return string namespace URI.
     */
    public function getNamespaceUri()
    {
        return $this->_namespaceUri;
    }

    /**
     * Gets the value.
     *
     * @return string The value.
     */
    public function getValue()
    {
        return $this->_value;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "localName":
                return $this->getLocalName();
            case "namespaceUri":
                return $this->getNamespaceUri();
            case "value":
                return $this->getValue();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }

    public function __isset($attr)
    {
        switch ($attr) {
            case "localName":
                return isset($this->_localName);
            case "namespaceUri":
                return isset($this->_namespaceUri);
            case "value":
                return isset($this->_value);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}