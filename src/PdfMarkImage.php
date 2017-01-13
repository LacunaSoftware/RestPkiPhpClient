<?php

namespace Lacuna\RestPkiClient;

/**
 * Class PdfMarkImage
 * @package Lacuna\RestPkiClient
 *
 * @property ResourceContentOrReference $resource
 * @property float|null $opacity
 */
class PdfMarkImage
{
    public $resource;
    public $opacity;

    /**
     * @param ResourceContentOrReference|null $imageContent
     * @param string|null $mimeType
     */
    public function __construct($imageContent = null, $mimeType = null)
    {
        $this->resource = new ResourceContentOrReference();
        if (!empty($imageContent)) {
            $this->resource->content = base64_encode($imageContent);
        }
        if (!empty($mimeType)) {
            $this->resource->mimeType = $mimeType;
        }
    }
}
