<?php

namespace Lacuna\RestPki;

/**
 * Class PdfMarkTextElement
 * @package Lacuna\RestPki
 *
 * @property array $textSections
 */
class PdfMarkTextElement extends PdfMarkElement
{
    public $textSections;
    public $align;

    /**
     * @param mixed|null $relativeContainer
     * @param array|null $textSections
     */
    public function __construct($relativeContainer = null, $textSections = null)
    {
        parent::__construct(PdfMarkElementType::TEXT, $relativeContainer);
        if (empty($textSections)) {
            $this->textSections = array();
        } else {
            $this->textSections = $textSections;
        }
        $this->align = 'Left';
    }
}
