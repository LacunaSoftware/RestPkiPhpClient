<?php

namespace Lacuna\RestPkiClient;

/**
 * @internal
 *
 * Class FileModel
 * @package Lacuna\RestPkiClient
 */
class FileModel
{
    /** @var string */
    public $content;

    /** @var string */
    public $blobToken;

    /** @var string */
    public $mimeType;

    public static function fromContentBinary($content) {
        $model = new self();
        $model->content = base64_encode($content);
        return $model;
    }

    public static function fromBlobToken($blobToken) {
        $model = new self();
        $model->blobToken = $blobToken;
        return $model;
    }
}
