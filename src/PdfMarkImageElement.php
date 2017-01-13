<?php

namespace Lacuna\RestPkiClient;

/**
 * Class PdfMarkImageElement
 * @package Lacuna\RestPkiClient
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
