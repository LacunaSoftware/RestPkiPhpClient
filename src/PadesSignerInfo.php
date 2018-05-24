<?php

namespace Lacuna\RestPki;

/**
 * Class PadesSignerInfo
 * @package Lacuna\RestPki
 *
 * @property-read $isDocumentTimestamp bool
 * @property-read $signatureFieldName mixed
 */
class PadesSignerInfo extends CadesSignerInfo
{
    public $_isDocumentTimestamp;
    public $_signatureFieldName;


    public function __construct($model)
    {
        parent::__construct($model);
        $this->_isDocumentTimestamp = $model->isDocumentTimestamp;
        $this->_signatureFieldName = $model->signatureFieldName;
    }

    /**
     * Gets the info if the document is a timestamp.
     *
     * @return bool The info if the document is a timestamp.
     */
    public function getIsDocumentTimestamp()
    {
        return $this->_isDocumentTimestamp;
    }

    /**
     * Get the signature field name.
     *
     * @return mixed The signature field name
     */
    public function getSignatureFieldName()
    {
        return $this->_signatureFieldName;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case "isDocumentTimestamp":
                return $this->getIsDocumentTimestamp();
            case "signatureFieldName":
                return $this->getSignatureFieldName();
            default:
                return parent::__get($prop);
        }
    }
}