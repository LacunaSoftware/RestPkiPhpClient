<?php

namespace Lacuna\RestPki\Client;

/**
 * Class DigestAlgorithm
 * @package Lacuna\RestPki\Client
 *
 * @property string $id The ID of the algorithm on Rest PKI's API
 * @property string $name A friendly name for the algorithm
 * @property string $phpId The algorithm name as expected by PHP's standard <b>hash_xxx</b> functions
 */
class DigestAlgorithm
{
    const MD5 = 'MD5';
    const SHA1 = 'SHA1';
    const SHA256 = 'SHA256';
    const SHA384 = 'SHA384';
    const SHA512 = 'SHA512';

    private $id;

    private function __construct($id)
    {
        switch ($id) {
            case MD5:
            case SHA1:
            case SHA256:
            case SHA384:
            case SHA512:
                $this->id = $id;
                break;
            default:
                throw new \RuntimeException("Unsupported digest algorithm: " . $id);
        }
    }

    /**
     * @return DigestAlgorithm The MD5 digest algorithm
     */
    public static function getMD5() {
        return new DigestAlgorithm(MD5);
    }

    /**
     * @return DigestAlgorithm The SHA-1 digest algorithm
     */
    public static function getSHA1() {
        return new DigestAlgorithm(SHA1);
    }

    /**
     * @return DigestAlgorithm The SHA-256 digest algorithm
     */
    public static function getSHA256() {
        return new DigestAlgorithm(SHA256);
    }

    /**
     * @return DigestAlgorithm The SHA-384 digest algorithm
     */
    public static function getSHA384() {
        return new DigestAlgorithm(SHA384);
    }

    /**
     * @return DigestAlgorithm The SHA-512 digest algorithm
     */
    public static function getSHA512() {
        return new DigestAlgorithm(SHA512);
    }

    /**
     * @param string $apiDigestAlg Digest algorithm as expressed on Rest PKI's API
     * @return DigestAlgorithm
     */
    public static function getInstanceByApiAlgorithm($apiDigestAlg) {
        return new DigestAlgorithm($apiDigestAlg);
    }

    /**
     * @return string The ID of the algorithm on Rest PKI's API
     */
    public function getAlgorithm() {
        return $this->id;
    }

    /**
     * @return string A friendly name for the algorithm
     */
    public function getName() {
        switch ($this->id) {
            case MD5:
                return 'MD5';
            case SHA1:
                return 'SHA-1';
            case SHA256:
                return 'SHA-256';
            case SHA384:
                return 'SHA-384';
            case SHA512:
                return 'SHA-512';
            default:
                throw new \RuntimeException(); // should not happen
        }
    }

    /**
     * @return string The algorithm name as expected by PHP's standard <b>hash_xxx</b> functions
     */
    public function getPhpId() {
        switch ($this->id) {
            case MD5:
                return 'md5';
            case SHA1:
                return 'sha1';
            case SHA256:
                return 'sha256';
            case SHA384:
                return 'sha384';
            case SHA512:
                return 'sha512';
            default:
                throw new \RuntimeException(); // should not happen
        }
    }

    /**
     * @return int The <b>MHASH hash ID</b>: one of the <b>MHASH_hashname</b> constants to be used with the <b>mhash_xxx</b> functions
     */
    public function getHashId()
    {
        switch ($this->id) {
            case MD5:
                return MHASH_MD5;
            case SHA1:
                return MHASH_SHA1;
            case SHA256:
                return MHASH_SHA256;
            case SHA384:
                return MHASH_SHA384;
            case SHA512:
                return MHASH_SHA512;
            default:
                throw new \RuntimeException(); // should not happen
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case "id":
                return $this->getAlgorithm();
            case "phpId":
                return $this->getPhpId();
            case "name":
                return $this->getName();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name);
                return null;
        }
    }
}
