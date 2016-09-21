<?php

namespace Lacuna\RestPki\Client;

class XmlIdResolutionTable
{

    private $model;

    public function __construct($includeXmlIdGlobalAttribute = null)
    {
        $this->model = array(
            'elementIdAttributes' => array(),
            'globalIdAttributes' => array(),
            'includeXmlIdAttribute' => $includeXmlIdGlobalAttribute
        );
    }

    public function addGlobalIdAttribute($idAttributeLocalName, $idAttributeNamespace = null)
    {
        $this->model['globalIdAttributes'][] = array(
            'localName' => $idAttributeLocalName,
            'namespace' => $idAttributeNamespace
        );
    }

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

    public function toModel()
    {
        return $this->model;
    }
}