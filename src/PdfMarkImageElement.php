<?php

namespace Lacuna\RestPki\Client;

/**
 * Class PdfMarkImageElement
 * @package Lacuna\RestPki\Client
 *
 * @property ResourceContentOrReference @image
 */
class PdfMarkImageElement extends PdfMarkElement
{
    public $image;

    /**
     * @param mixed|null $relativeContainer
     * @param ResourceContentOrReference|null $image
     */
    public function __construct($relativeContainer = null, $image = null)
    {
        parent::__construct(PdfMarkElementType::IMAGE, $relativeContainer);
        $this->image = $image;
    }
}
