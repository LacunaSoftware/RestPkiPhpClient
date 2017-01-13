<?php

namespace Lacuna\RestPki;

/**
 * Class ResourceContentOrReference
 * @package Lacuna\RestPki
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
