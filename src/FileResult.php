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
     * @param $model
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
     * @return string
     */
    public function getContent() {
        if (isset($this->model->content)) {
            return $this->model->content;
        } else {
            return $this->client->_downloadContent($this->model->url);
        }
    }

    /**
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
