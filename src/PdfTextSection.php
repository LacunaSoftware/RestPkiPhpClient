<?php

namespace Lacuna\RestPki;

/**
 * Class PdfTextSection
 * @package Lacuna\RestPki
 *
 * @property string $style
 * @property string $text
 * @property Color $color
 * @property float|null $fontSize
 */
class PdfTextSection
{
    public $style;
    public $text;
    public $color;
    public $fontSize;

    /**
     * @param string|null $text
     * @param Color|null $color
     * @param float|null $fontSize
     */
    public function __construct($text = null, $color = null, $fontSize = null)
    {
        $this->style = PdfTextStyle::NORMAL;
        $this->text = $text;
        $this->fontSize = $fontSize;
        if (empty($color)) {
            $this->color = new Color('#000000'); // Black
        } else {
            $this->color = $color;
        }
    }
}
