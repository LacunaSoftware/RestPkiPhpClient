<?php

namespace Lacuna\RestPkiClient;

/**
 * Class ResourceContentOrReference
 * @package Lacuna\RestPkiClient
 *
 * @property string $url
 * @property string $mimeType
 * @property string $content
 */
class ResourceContentOrReference
{
    public $url;
    public $mimeType;
    public $content;

    public function __construct()
    {
    }
}
