<?php

namespace Lacuna\RestPki;

/**
 * @internal
 *
 * Class FileReference
 * @package Lacuna\RestPki
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
     * @param string $contentRaw
     * @return FileReference
     */
    public static function fromContentRaw($contentRaw)
    {
        $obj = new self();
        $obj->content = $contentRaw;
        return $obj;
    }

    /**
     * @param string $contentBase64
     * @return FileReference
     */
    public static function fromContentBase64($contentBase64)
    {
        $obj = new self();
        $obj->content = base64_decode($contentBase64);
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
                return FileModel::fromContentRaw($this->content);
            } else {
                $model = $client->_uploadOrReadContent($this->content);
                if (isset($model->blobToken)) {
                    $this->blobToken = $model->blobToken;
                }
                return $model;
            }

        } else {

            if (filesize($this->path) < $client->multipartUploadThreshold) {
                return FileModel::fromContentRaw(file_get_contents($this->path));
            } else {
                return $client->_uploadOrReadFile($this->path);
            }

        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContentRaw()
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
        return base64_encode($this->getContentRaw());
    }

    /**
     * @param DigestAlgorithm[] $algorithms
     * @return array
     */
    public function computeDataHashes($algorithms)
    {

        $dataHashes = array();
        foreach ($algorithms as $algorithm) {
            $digestHexValue = null;
            if (isset($this->path)) {
                $digestHexValue = hash_file($algorithm->phpId, $this->path);
            } else {
                $digestHexValue = hash($algorithm->phpId, $this->getContentRaw());
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
