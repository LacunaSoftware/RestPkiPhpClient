<?php

namespace Lacuna\RestPki\Client;

class ValidationResults
{

    private $errors;
    private $warnings;
    private $passedChecks;

    public function __construct($model)
    {
        $this->errors = self::convertItems($model->errors);
        $this->warnings = self::convertItems($model->warnings);
        $this->passedChecks = self::convertItems($model->passedChecks);
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getChecksPerformed()
    {
        return count($this->errors) + count($this->warnings) + count($this->passedChecks);
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function hasWarnings()
    {
        return !empty($this->warnings);
    }

    public function __toString()
    {
        return $this->toString(0);
    }

    public function toString($indentationLevel)
    {
        $tab = str_repeat("\t", $indentationLevel);
        $text = '';
        $text .= $this->getSummary($indentationLevel);
        if ($this->hasErrors()) {
            $text .= "\n{$tab}Errors:\n";
            $text .= self::joinItems($this->errors, $indentationLevel);
        }
        if ($this->hasWarnings()) {
            $text .= "\n{$tab}Warnings:\n";
            $text .= self::joinItems($this->warnings, $indentationLevel);
        }
        if (!empty($this->passedChecks)) {
            $text .= "\n{$tab}Passed checks:\n";
            $text .= self::joinItems($this->passedChecks, $indentationLevel);
        }
        return $text;
    }

    public function getSummary($indentationLevel = 0)
    {
        $tab = str_repeat("\t", $indentationLevel);
        $text = "{$tab}Validation results: ";
        if ($this->getChecksPerformed() === 0) {
            $text .= 'no checks performed';
        } else {
            $text .= "{$this->getChecksPerformed()} checks performed";
            if ($this->hasErrors()) {
                $text .= ', ' . count($this->errors) . ' errors';
            }
            if ($this->hasWarnings()) {
                $text .= ', ' . count($this->warnings) . ' warnings';
            }
            if (!empty($this->passedChecks)) {
                if (!$this->hasErrors() && !$this->hasWarnings()) {
                    $text .= ", all passed";
                } else {
                    $text .= ', ' . count($this->passedChecks) . ' passed';
                }
            }
        }
        return $text;
    }

    private static function convertItems($items)
    {
        $converted = array();
        foreach ($items as $item) {
            $converted[] = new ValidationItem($item);
        }
        return $converted;
    }

    private static function joinItems($items, $indentationLevel)
    {
        $text = '';
        $isFirst = true;
        $tab = str_repeat("\t", $indentationLevel);
        foreach ($items as $item) {
            /** @var ValidationItem $item */
            if ($isFirst) {
                $isFirst = false;
            } else {
                $text .= "\n";
            }
            $text .= "{$tab}- ";
            $text .= $item->toString($indentationLevel);
        }
        return $text;
    }

}