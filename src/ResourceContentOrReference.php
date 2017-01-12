<?php

namespace Lacuna\RestPki\Client;

/**
 * Class ResourceContentOrReference
 * @package Lacuna\RestPki\Client
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
