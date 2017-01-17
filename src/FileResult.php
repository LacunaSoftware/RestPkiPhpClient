<?php

namespace Lacuna\RestPki;

/**
 * Class FileResult
 * @package Lacuna\RestPki
 */
class FileResult
{

    /** @var RestPkiClient */
    private $client;
    private $model;

    /**
     * @internal
     *
     * @param $restPkiClient RestPkiClient
     * @param mixed $model
     */
    public function __construct($restPkiClient, $model)
    {
        $this->client = $restPkiClient;
        $this->model = $model;
    }

    /**
     * @internal
     * @return mixed
     */
    public function _getModel()
    {
        return $this->model;
    }

    /**
     * Returns the raw (binary) content of the file. For large files, prefer the function writeToFile() to avoid
     * memory allocation issues.
     *
     * @return string
     */
    public function getContentRaw() {
        if (isset($this->model->content)) {
            return $this->model->content;
        } else {
            return $this->client->_downloadContent($this->model->url);
        }
    }

    /**
     * Returns the base64-encoded content of the file. For large files, prefer the function writeToFile() to avoid
     * memory allocation issues.
     *
     * @return string
     */
    public function getContentBase64() {
        return base64_encode($this->getContentRaw());
    }

    /**
     * Writes the file to a local path. For large files, prefer this function to avoid memory allocation issues.
     *
     * @param string $path
     */
    public function writeToFile($path) {
        if (isset($this->model->content)) {
            file_put_contents($path, base64_decode($this->model->content));
        } else {
            $this->client->_downloadToFile($this->model->url, $path);
        }
    }
}
