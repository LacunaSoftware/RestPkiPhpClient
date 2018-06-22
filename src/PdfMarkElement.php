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
    public $rotation = 0;
    public $opacity = 100;

    /**
     * @param string $elementType
     * @param mixed|null $relativeContainer
     */
    public function __construct($elementType, $relativeContainer = null)
    {
        $this->elementType = $elementType;
        $this->relativeContainer = $relativeContainer;
    }
}
