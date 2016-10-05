<?php

namespace Lacuna\RestPki\Client;

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
