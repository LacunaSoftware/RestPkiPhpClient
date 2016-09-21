<?php

namespace Lacuna\RestPki\Client;

class PdfMarkElement
{
    public $elementType;
    public $relativeContainer;
    public $rotation;

    public function __construct()
    {
        $args = func_get_args();
        if (sizeof($args) == 1) { // Case (elementType)
            $this->elementType = $args[0];
            $this->rotation = 0;
        } else {
            if (sizeof($args) == 2) { // Case (elementType, relativeContainer)
                $this->rotation = 0;
                $this->elementType = $args[0];
                $this->relativeContainer = $args[1];
            } else {
                throw new \InvalidArgumentException("Invalid parameters passed to the PdfMarkElement's Constructor.");
            }
        }
    }
}