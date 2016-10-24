<?php

namespace Lacuna\RestPki\Client;

class PdfMarkImageElement extends PdfMarkElement
{
    public $image;

    public function __construct()
    {
        $args = func_get_args();
        if (sizeof($args) == 0) { // Case ()
            parent::__construct(PdfMarkElementType::IMAGE);
        } else {
            if (sizeof($args) == 2) { // Case (relativeContainer, image)
                $this->image = $args[1];
                parent::__construct(PdfMarkElementType::IMAGE, $args[0]);
            } else {
                throw new \InvalidArgumentException("Invalid parameters passed to the PdfMarkImageElement's constructor.");
            }
        }
    }
}