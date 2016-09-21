<?php

namespace Lacuna\RestPki\Client;

class PdfTextSection
{
    public $style;
    public $text;
    public $color;
    public $fontSize;

    public function __construct()
    {
        $args = func_get_args();
        if (sizeof($args) == 0) { // Case ()
            $this->style = PdfTextStyle::NORMAL;
            $this->color = new Color('#000000'); // Black
        } else {
            if (sizeof($args) == 2) { // Case (text, color)
                $this->text = $args[0];
                $this->color = $args[1];
                $this->fontSize = null;
            } else {
                if (sizeof($args) == 3) { // Case (text, color, fontSize)
                    $this->text = $args[0];
                    $this->color = $args[1];
                    $this->fontSize = $args[2];
                } else {
                    throw new \InvalidArgumentException("Invalid parameters passed to the PdfMarkImageElement's Constructor.");
                }
            }
        }
    }
}