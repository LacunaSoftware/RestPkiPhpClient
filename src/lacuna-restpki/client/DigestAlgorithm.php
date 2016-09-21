<?php

namespace Lacuna\RestPki\Client;

class DigestAlgorithm
{
    const MD5 = 'MD5';
    const SHA1 = 'SHA-1';
    const SHA256 = 'SHA-256';
    const SHA384 = 'SHA-384';
    const SHA512 = 'SHA-512';

    private $name;
    private $algorithm;

    private function __construct($name)
    {
        $this->name = constant('Lacuna\RestPki\Client\DigestAlgorithm::' . $name);
        $this->algorithm = $name;
    }

    public static function getInstanceByApiAlgorithm($algorithm)
    {
        if (defined('Lacuna\RestPki\Client\DigestAlgorithm::' . $algorithm)) {
            return new DigestAlgorithm($algorithm);
        } else {
            throw new \RuntimeException("Unsupported digest algorithm: " . $algorithm); // should not happen
        }
    }

    public function getHashId()
    {
        switch ($this->algorithm) {
            case 'MD5':
                return MHASH_MD5;
            case 'SHA1':
                return MHASH_SHA1;
            case 'SHA256':
                return MHASH_SHA256;
            case 'SHA384':
                return MHASH_SHA384;
            case 'SHA512':
                return MHASH_SHA512;
            default:
                throw new \RuntimeException("Could not get MessageDigest instance for algorithm " . $this->algorithm);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }
}
