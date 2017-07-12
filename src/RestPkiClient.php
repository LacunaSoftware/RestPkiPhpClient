<?php

namespace Lacuna\RestPki;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RestPkiClient
 * @package Lacuna\RestPki
 *
 * @property $multipartUploadThreshold int
 * @property $restPkiVersion string
 */
class RestPkiClient
{
    public $multipartUploadThreshold = 5 * 1024 * 1024; // 5 MB
    public $restPkiVersion;

    /**
     * @internal
     * @var int
     */
    public $_uploadCount = 0;

    private $endpointUrl;
    private $accessToken;
    private static $endpointVersions = array();

    public function __construct($endpointUrl, $accessToken)
    {
        $this->endpointUrl = $endpointUrl;
        $this->accessToken = $accessToken;
    }

    public function getRestClient()
    {
        $headers = [
            'Accept' => 'application/json'
        ];
        if (!empty($this->accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }
        $client = new Client([
            'base_uri' => $this->endpointUrl,
            'headers' => $headers,
            'http_errors' => false
        ]);
        return $client;
    }

    /**
     * @param $url string
     * @return mixed
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws RestUnreachableException
     * @throws ValidationException
     */
    public function get($url)
    {
        $verb = 'GET';
        $client = $this->getRestClient();
        $httpResponse = null;
        try {
            $httpResponse = $client->get($url);
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->checkResponse($verb, $url, $httpResponse);
        return json_decode($httpResponse->getBody());
    }

    /**
     * @param $url string
     * @param $data mixed
     * @return mixed
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws RestUnreachableException
     * @throws ValidationException
     */
    public function post($url, $data)
    {
        $verb = 'POST';
        $client = $this->getRestClient();
        $httpResponse = null;
        try {
            if (empty($data)) {
                $httpResponse = $client->post($url);
            } else {
                $httpResponse = $client->post($url, array('json' => $data));
            }
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->checkResponse($verb, $url, $httpResponse);
        return json_decode($httpResponse->getBody());
    }

    /**
     * @param $url
     * @param $data
     * @return string
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws RestUnreachableException
     * @throws ValidationException
     */
    public function postRaw($url, $data)
    {
        $verb = 'POST';
        $client = $this->getRestClient();
        $httpResponse = null;
        try {
            $httpResponse = $client->post($url, array('body' => $data));
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->checkResponse($verb, $url, $httpResponse);
        return $httpResponse->getHeaderLine('ETag');
    }

    /**
     * @param $verb string
     * @param $url string
     * @param $httpResponse ResponseInterface
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws ValidationException
     */
    private function checkResponse($verb, $url, $httpResponse)
    {
        $statusCode = $httpResponse->getStatusCode();
        if ($statusCode < 200 || $statusCode > 299) {
            $ex = null;
            try {
                $response = json_decode($httpResponse->getBody());
                if ($statusCode == 422 && !empty($response->code)) {
                    if ($response->code == "ValidationError") {
                        $vr = new ValidationResults($response->validationResults);
                        $ex = new ValidationException($verb, $url, $vr);
                    } else {
                        $ex = new RestPkiException($verb, $url, $response->code, $response->detail);
                    }
                } else {
                    $ex = new RestErrorException($verb, $url, $statusCode, $response->message);
                }
            } catch (\Exception $e) {
                $ex = new RestErrorException($verb, $url, $statusCode);
            }
            throw $ex;
        }
    }

    public function getAuthentication()
    {
        return new Authentication($this);
    }

    /**
     * @param string $path
     * @return string The blob token referencing the file uploaded.
     */
    public function uploadFileFromPath($path)
    {
        return $this->_uploadFile($path);
    }

    /**
     * @param string $contentRaw
     * @return string The blob token referencing the file uploaded.
     */
    public function uploadFileFromContentRaw($contentRaw)
    {
        return $this->_uploadContent($contentRaw);
    }

    /**
     * @param string $contentBase64
     * @return string The blob token referencing the file uploaded.
     */
    public function uploadFileFromContentBase64($contentBase64)
    {
        return $this->_uploadContent(base64_decode($contentBase64));
    }

    /**
     * @internal
     *
     * @param $path
     * @return FileModel The resulting FileModel with either a blobToken or a content, depending on the Rest PKI version
     */
    public function _uploadOrReadFile($path)
    {
        $apiVersion = $this->_getApiVersion('MultipartUpload');

        switch ($apiVersion) {
            case 0:
                return FileModel::fromContentRaw(file_get_contents($path));
            default:
                return FileModel::fromBlobToken(self::_uploadFile($path));
        }
    }

    /**
     * @internal
     *
     * @param $content
     * @return FileModel The resulting FileModel with either a blobToken or a content, depending on the Rest PKI version
     */
    public function _uploadOrReadContent($content)
    {

        $apiVersion = $this->_getApiVersion('MultipartUpload');

        switch ($apiVersion) {
            case 0:
                return FileModel::fromContentRaw($content);
            default:
                return FileModel::fromBlobToken(self::_uploadContent($content));
        }
    }

    private function _uploadFile($path)
    {

        // Begin the upload

        $beginResponse = $this->post('Api/MultipartUploads', null);
        $blobToken = $beginResponse->blobToken;
        $partSize = $beginResponse->partSize;

        // Upload parts

        $handle = fopen($path, 'rb');
        $partNumber = 0;
        $partETags = array();
        while (!feof($handle)) {

            $buffer = fread($handle, $partSize);

            $partETag = $this->postRaw("Api/MultipartUploads/{$blobToken}/{$partNumber}", $buffer);
            array_push($partETags, $partETag);

            $partNumber += 1;
        }
        fclose($handle);

        $endRequest = array(
            'partETags' => $partETags
        );
        $this->post("Api/MultipartUploads/{$blobToken}", $endRequest);

        $this->_uploadCount += 1;

        return $blobToken;
    }

    private function _uploadContent($content)
    {

        $len = strlen($content);

        // Begin the upload

        $beginResponse = $this->post('MultipartUploads', null);
        $blobToken = $beginResponse->blobToken;
        $partSize = $beginResponse->partSize;

        // Upload parts

        $partNumber = 0;
        $offset = 0;
        $partETags = array();

        while ($offset < $len) {

            $partLen = (int)min($len - $offset, $partSize);
            $buffer = substr($content, $offset, $partLen);

            $partETag = $this->postRaw("MultipartUploads/{$blobToken}/{$partNumber}", $buffer);
            array_push($partETags, $partETag);

            $partNumber += 1;
            $offset += $partLen;
        }

        $endRequest = array(
            'partETags' => $partETags
        );
        $this->post("MultipartUploads/{$blobToken}", $endRequest);

        $this->_uploadCount += 1;

        return $blobToken;
    }

    /**
     * @internal
     *
     * @param $url
     * @return string
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws RestUnreachableException
     * @throws ValidationException
     */
    public function _downloadContent($url)
    {
        $verb = 'GET';
        $client = $this->getRestClient();
        $httpResponse = null;
        try {
            $httpResponse = $client->get($url);
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->checkResponse($verb, $url, $httpResponse);
        return $httpResponse->getBody();
    }

    /**
     * @internal
     *
     * @param $url
     * @param $path
     * @throws RestErrorException
     * @throws RestPkiException
     * @throws RestUnreachableException
     * @throws ValidationException
     */
    public function _downloadToFile($url, $path)
    {
        $handle = fopen($path, 'wb');

        try {

            $verb = 'GET';
            $client = $this->getRestClient();
            $httpResponse = null;
            try {
                $httpResponse = $client->get($url, ['sink' => $handle]);
            } catch (TransferException $ex) {
                throw new RestUnreachableException($verb, $url, $ex);
            }
            $this->checkResponse($verb, $url, $httpResponse);

        } finally {
            fclose($handle);
        }
    }

    /**
     * @internal
     *
     * @param $api
     * @return int
     */
    public function _getApiVersion($api)
    {
        $v = $this->restPkiVersion;

        if (!isset($v)) {
            $v = self::tryGetEndpointVersion($this->endpointUrl);
        }

        switch ($api) {

            case 'StartCades':
                if (version_compare($v, '1.11') >= 0) {
                    return 3;
                } elseif (version_compare($v, '1.10') >= 0) {
                    return 2;
                } else {
                    return 1;
                }

            case 'CompleteCades':
                if (version_compare($v, '1.11') >= 0) {
                    return 2;
                } else {
                    return 1;
                }

            case 'StartPades':
                if (version_compare($v, '1.11') >= 0) {
                    return 2;
                } else {
                    return 1;
                }

            case 'CompletePades':
                if (version_compare($v, '1.11') >= 0) {
                    return 2;
                } else {
                    return 1;
                }

            case 'MultipartUpload':
                if (version_compare($v, '1.11') >= 0) {
                    return 1;
                } else {
                    return 0;
                }

            case 'AddPdfMarks':
                if (version_compare($v, '1:13') >= 0) {
                    return 1;
                } else {
                    return 0;
                }

        }

        return null; // should not happen
    }

    /**
     * @param $endpointUrl string
     * @return int|null
     */
    private static function tryGetEndpointVersion($endpointUrl)
    {
        if (isset(self::$endpointVersions[$endpointUrl])) {
            return self::$endpointVersions[$endpointUrl];
        }

        try {
            $client = new self($endpointUrl, null);
            $response = $client->get('Api/System/Info');
            $version = $response->productVersion;
        } catch (\Exception $e) {
            return null;
        }

        self::$endpointVersions[$endpointUrl] = $version;
        return $version;
    }
}
