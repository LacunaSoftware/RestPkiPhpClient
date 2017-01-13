<?php

namespace Lacuna\RestPki;

/**
 * Class ValidationException
 * @package Lacuna\RestPki
 *
 * @property-read $validationResults ValidationResults
 */
class ValidationException extends RestException
{

    /** @var ValidationResults */
    private $_validationResults;

    /**
     * @param string $verb
     * @param string $url
     * @param ValidationResults $validationResults
     */
    public function __construct($verb, $url, $validationResults)
    {
        parent::__construct($validationResults->__toString(), $verb, $url);
        $this->_validationResults = $validationResults;
    }

    /**
     * @return ValidationResults
     */
    public function getValidationResults()
    {
        return $this->_validationResults;
    }

    public function __get($name)
    {
        switch ($name) {
            case "validationResults":
                return $this->_validationResults;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
