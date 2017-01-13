<?php

namespace Lacuna\RestPkiClient;

/**
 * Class ValidationItem
 * @package Lacuna\RestPkiClient
 *
 * @property-read $type string
 * @property-read $message string
 * @property-read $detail string
 */
class ValidationItem
{
    /** @var ValidationResults */
    private $innerValidationResults;

    private $_type;
    private $_message;
    private $_detail;

    public function __construct($model)
    {
        $this->_type = $model->type;
        $this->_message = $model->message;
        $this->_detail = $model->detail;
        if ($model->innerValidationResults !== null) {
            $this->innerValidationResults = new ValidationResults($model->innerValidationResults);
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->_detail;
    }

    public function __toString()
    {
        return $this->toString(0);
    }

    /**
     * @param int $indentationLevel
     * @return string
     */
    public function toString($indentationLevel)
    {
        $text = '';
        $text .= $this->_message;
        if (!empty($this->_detail)) {
            $text .= " ({$this->_detail})";
        }
        if ($this->innerValidationResults !== null) {
            $text .= "\n";
            $text .= $this->innerValidationResults->toString($indentationLevel + 1);
        }
        return $text;
    }

    public function __get($name)
    {
        switch ($name) {
            case "type":
                return $this->_type;
            case "message":
                return $this->_message;
            case "detail":
                return $this->_detail;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
