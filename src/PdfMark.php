<?php

namespace Lacuna\RestPki;

/**
 * Class PdfMark
 * @package Lacuna\RestPki
 *
 * @property mixed $container
 * @property float $borderWidth
 * @property Color|null $borderColor
 * @property Color|null $backgroundColor
 * @property array $elements
 */
class PdfMark
{
    public $container;
    public $borderWidth;
    public $borderColor;
    public $backgroundColor;
    public $elements;

    public function __construct()
    {
        $this->borderWidth = 0.0;
        $this->borderColor = new Color("#000000"); // Black
        $this->backgroundColor = new Color("#FFFFFF", 0); // Transparent
        $this->elements = [];
    }
}
