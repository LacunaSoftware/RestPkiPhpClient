<?php

namespace Lacuna\RestPki;

/**
 * Class PdfMarkElement
 * @package Lacuna\RestPki
 *
 * @property string $elementType
 * @property mixed|null $relativeContainer
 * @property int $rotation
 * @property int $opacity
*/
class PdfMarkElement
{
    public $elementType;
    public $relativeContainer;
    public $rotation;
    public $opacity;

    /**
     * @param string $elementType
     * @param mixed|null $relativeContainer
     */
    public function __construct($elementType, $relativeContainer = null)
    {
        $this->rotation = 0;
        $this->elementType = $elementType;
        $this->relativeContainer = $relativeContainer;
        $this->opacity = 100;
    }
}
