<?php

namespace Lacuna\RestPki;

/**
 * Class XmlElementInfo
 * @package Lacuna\RestPki
 *
 * @property-read $localName string
 * @property-read $attributes XmlAttributeInfo[]
 * @property-read $nameSpaceUri string
 */
class XmlElementInfo
{
    private $_localName;
    private $_attributes = [];
    private $_nameSpaceUri;

    public function __construct($model)
    {
        $this->_localName = $model->localName;
        $this->_nameSpaceUri = $model->nameSpaceUri;
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
    public function getNameSpaceUri()
    {
        return $this->_nameSpaceUri;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case "localName":
                return $this->getLocalName();
            case "attributes":
                return $this->getAttributes();
            case "nameSpaceUri":
                return $this->getNameSpaceUri();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $attr);
                return null;
        }
    }
}