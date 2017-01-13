<?php

namespace Lacuna\RestPki\Client;

/**
 * @internal
 *
 * Class FileReference
 * @package Lacuna\RestPki\Client
 */
class FileReference
{
    private $path;
    private $blobToken;
    private $content;

    private function __construct()
    {
    }

    /**
     * @param $path string
     * @return FileReference
     */
    public static function fromFile($path)
    {
        $obj = new self();
        $obj->path = $path;
        return $obj;
    }

    /**
     * @param $content string
     * @return FileReference
     */
    public static function fromBinary($content)
    {
        $obj = new self();
        $obj->content = $content;
        return $obj;
    }

    /**
     * @param FileResult $result
     * @return FileReference
     */
    public static function fromResult($result)
    {
        $obj = new self();
        $model = $result->_getModel();
        if (isset($model->blobToken)) {
            $obj->blobToken = $model->blobToken;
        } else {
            $obj->content = $model->content;
        }
        return $obj;
    }

    /**
     * @param $client RestPkiClient
     * @return FileModel The FileModel representing the file.
     */
    public function uploadOrReference($client)
    {
        if (isset($this->blobToken)) {

            return FileModel::fromBlobToken($this->blobToken);

        } elseif (isset($this->content)) {

            if (strlen($this->content) < $client->multipartUploadThreshold) {
                return FileModel::fromContentBinary($this->content);
            } else {
                $model = $client->_uploadOrReadContent($this->content);
                if (isset($model->blobToken)) {
                    $this->blobToken = $model->blobToken;
                }
                return $model;
            }

        } else {

            if (filesize($this->path) < $client->multipartUploadThreshold) {
                return FileModel::fromContentBinary(file_get_contents($this->path));
            } else {
                return $client->_uploadOrReadFile($this->path);
            }

        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContentBinary()
    {
        if (isset($this->content)) {

            return $this->content;

        } elseif (isset($this->path)) {

            return file_get_contents($this->path);

        } else {

            // should not happen
            throw new \Exception("This file cannot be referenced by its content");

        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContentBase64()
    {
        return base64_encode($this->getContentBinary());
    }

    /**
     * @param DigestAlgorithm[] $algorithms
     * @return array
     */
    public function computeDataHashes($algorithms) {

        $dataHashes = array();
        foreach ($algorithms as $algorithm) {
            $digestHexValue = null;
            if (isset($this->path)) {
                $digestHexValue = hash_file($algorithm->phpId, $this->path);
            } else {
                $digestHexValue = hash($algorithm->phpId, $this->getContentBinary());
            }
            $dataHash = array(
                'algorithm' => $algorithm->id,
                'hexValue' => $digestHexValue
            );
            array_push($dataHashes, $dataHash);
        }
        return $dataHashes;
    }
}
