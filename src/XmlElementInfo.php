<?php

namespace Lacuna\RestPki;

/**
 * Class XmlElementInfo
 * @package Lacuna\RestPki
 *
 * @property-read $localName string
 * @property-read $attributes XmlAttributeInfo[]
 * @property-read $namespaceUri string
 */
class XmlElementInfo
{
    private $_localName;
    private $_attributes = [];
    private $_namespaceUri;

    public function __construct($model)
    {
        $this->_localName = $model->localName;
        $this->_namespaceUri = $model->namespaceUri;
        foreach ($model->attributes as $attribute) {
            $this->_attributes[] = new XmlAttributeInfo($attribute);
        }
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
     * Gets the attributes list.
     *
     * @return XmlAttributeInfo[] The attributes list.
     */
    public function getAttributes()
    {
        return $this->_attributes;
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

    public function __get($attr)
    {
        switch ($attr) {
            case "localName":
                return $this->getLocalName();
            case "attributes":
                return $this->getAttributes();
            case "namespaceUri":
                return $this->getNamespaceUri();
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
            case "attributes":
                return isset($this->_attributes);
            case "namespaceUri":
                return isset($this->_namespaceUri);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}