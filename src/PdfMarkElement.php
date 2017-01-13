<?php

namespace Lacuna\RestPki;

/**
 * Class PdfMarkElement
 * @package Lacuna\RestPki
 *
 * @property string $elementType
 * @property mixed|null $relativeContainer
 * @property int $rotation
 */
class PdfMarkElement
{
    public $elementType;
    public $relativeContainer;
    public $rotation;

    /**
     * @param string $elementType
     * @param mixed|null $relativeContainer
     */
    public function __construct($elementType, $relativeContainer = null)
    {
        $this->rotation = 0;
        $this->elementType = $elementType;
        $this->relativeContainer = $relativeContainer;
    }
}
