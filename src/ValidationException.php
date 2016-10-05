<?php

namespace Lacuna\RestPki\Client;

class ValidationException extends RestException
{

    /** @var ValidationResults */
    private $validationResults;

    public function __construct($verb, $url, ValidationResults $validationResults)
    {
        parent::__construct($validationResults->__toString(), $verb, $url);
        $this->validationResults = $validationResults;
    }

    public function getValidationResults()
    {
        return $this->validationResults;
    }
}