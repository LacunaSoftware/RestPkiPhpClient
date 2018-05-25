<?php

namespace Lacuna\RestPki;


class XmlAttributeInfo
{
    private $_localName;
    private $_nameSpaceUri;
    private $_value;

    public function __construct($model)
    {
        $this->_localName = $model->localName;
        $this->_nameSpaceUri = $model->nameSpaceUri;
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
    public function getNameSpaceUri()
    {
        return $this->_nameSpaceUri;
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
            case "nameSpaceUri":
                return $this->getNameSpaceUri();
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
            case "nameSpaceUri":
                return isset($this->_nameSpaceUri);
            case "value":
                return isset($this->_value);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}