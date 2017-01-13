<?php

namespace Lacuna\RestPkiClient;

/**
 * Class PdfMarkTextElement
 * @package Lacuna\RestPkiClient
 *
 * @property array $textSections
 */
class PdfMarkTextElement extends PdfMarkElement
{
    public $textSections;

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
    }
}
