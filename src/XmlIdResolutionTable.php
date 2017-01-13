<?php

namespace Lacuna\RestPki;

/**
 * Class XmlIdResolutionTable
 * @package Lacuna\RestPki
 */
class XmlIdResolutionTable
{
    private $model;

    /**
     * @param bool|null $includeXmlIdGlobalAttribute
     */
    public function __construct($includeXmlIdGlobalAttribute = null)
    {
        $this->model = array(
            'elementIdAttributes' => array(),
            'globalIdAttributes' => array(),
            'includeXmlIdAttribute' => $includeXmlIdGlobalAttribute
        );
    }

    /**
     * @param string $idAttributeLocalName
     * @param string|null $idAttributeNamespace
     */
    public function addGlobalIdAttribute($idAttributeLocalName, $idAttributeNamespace = null)
    {
        $this->model['globalIdAttributes'][] = array(
            'localName' => $idAttributeLocalName,
            'namespace' => $idAttributeNamespace
        );
    }

    /**
     * @param string $elementLocalName
     * @param string|null $elementNamespace
     * @param string $idAttributeLocalName
     * @param string|null $idAttributeNamespace
     */
    public function setElementIdAttribute(
        $elementLocalName,
        $elementNamespace,
        $idAttributeLocalName,
        $idAttributeNamespace = null
    ) {
        $this->model['elementIdAttributes'][] = array(
            'element' => array(
                'localName' => $elementLocalName,
                'namespace' => $elementNamespace
            ),
            'attribute' => array(
                'localName' => $idAttributeLocalName,
                'namespace' => $idAttributeNamespace
            )
        );
    }

    /**
     * @internal
     *
     * @return array
     */
    public function toModel()
    {
        return $this->model;
    }
}
