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
 * @property string $pageOption
 * @property int $pageOptionNumber
 */
class PdfMark
{
    public $container;
    public $borderWidth = 0.0;
    public $borderColor;
    public $backgroundColor;
    public $elements = [];
    public $pageOption = PdfMarkPageOptions::ALL_PAGES;
    public $pageOptionNumber;

    public function __construct()
    {
        $this->borderColor = new Color("#000000"); // Black
        $this->backgroundColor = new Color("#FFFFFF", 0); // Transparent
    }
}
