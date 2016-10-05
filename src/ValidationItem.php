<?php

namespace Lacuna\RestPki\Client;

class ValidationItem
{

    private $type;
    private $message;
    private $detail;
    /** @var ValidationResults */
    private $innerValidationResults;

    public function __construct($model)
    {
        $this->type = $model->type;
        $this->message = $model->message;
        $this->detail = $model->detail;
        if ($model->innerValidationResults !== null) {
            $this->innerValidationResults = new ValidationResults($model->innerValidationResults);
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    public function __toString()
    {
        return $this->toString(0);
    }

    public function toString($indentationLevel)
    {
        $text = '';
        $text .= $this->message;
        if (!empty($this->detail)) {
            $text .= " ({$this->detail})";
        }
        if ($this->innerValidationResults !== null) {
            $text .= "\n";
            $text .= $this->innerValidationResults->toString($indentationLevel + 1);
        }
        return $text;
    }

}