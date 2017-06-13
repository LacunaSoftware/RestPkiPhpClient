<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureAlgorithm
 * @package Lacuna\RestPki
 *
 * @property string $id The ID of the algorithm on Rest PKI's API
 * @property string $name A friendly name for the algorithm
 */
class SignatureAlgorithm
{
    const MD5WithRSA = "MD5WithRSA";
    const SHA1WithRSA = "SHA1WithRSA";
    const SHA256WithRSA = "SHA256WithRSA";
    const SHA384WithRSA = "SHA384WithRSA";
    const SHA512WithRSA = "SHA512WithRSA";

    private $id;

    private function __construct($id)
    {
        switch ($id) {
            case self::MD5WithRSA:
            case self::SHA1WithRSA:
            case self::SHA256WithRSA:
            case self::SHA384WithRSA:
            case self::SHA512WithRSA:
                $this->id = $id;
                break;
            default:
                throw new \RuntimeException("Unsupported signature algorithm: " . $id);
        }
    }

    /**
     * @return SignatureAlgorithm The MD5 signature algorithm
     */
    public static function getMD5WithRSA()
    {
        return new SignatureAlgorithm(self::MD5WithRSA);
    }

    /**
     * @return SignatureAlgorithm The MD5 signature algorithm
     */
    public static function getSHA1WithRSA()
    {
        return new SignatureAlgorithm(self::SHA1WithRSA);
    }

    /**
     * @return SignatureAlgorithm The MD5 signature algorithm
     */
    public static function getSHA256WithRSA()
    {
        return new SignatureAlgorithm(self::SHA256WithRSA);
    }

    /**
     * @return SignatureAlgorithm The MD5 signature algorithm
     */
    public static function getSHA384WithRSA()
    {
        return new SignatureAlgorithm(self::SHA384WithRSA);
    }

    /**
     * @return SignatureAlgorithm The MD5 signature algorithm
     */
    public static function getSHA512WithRSA()
    {
        return new SignatureAlgorithm(self::SHA512WithRSA);
    }

    /**
     * @param string $apiDigestAlg Signature algorithm as expressed on Rest PKI's API
     * @return SignatureAlgorithm
     */
    public static function getInstanceByApiAlgorithm($apiSignatureAlg)
    {
        return new SignatureAlgorithm($apiSignatureAlg);
    }

    /**
     * @return string The ID of the algorithm on Rest PKI's API
     */
    public function getAlgorithm()
    {
        return $this->id;
    }

    /**
     * @return string A friendly name for the algorithm
     */
    public function getName()
    {
        switch ($this->id) {
            case self::MD5WithRSA:
                return 'MD5 with RSA';
            case self::SHA1WithRSA:
                return 'SHA-1 with RSA';
            case self::SHA256WithRSA:
                return 'SHA-256 with RSA';
            case self::SHA384WithRSA:
                return 'SHA-384 with RSA';
            case self::SHA512WithRSA:
                return 'SHA-512 with RSA';
            default:
                throw new \RuntimeException(); // should not happen
        }
    }
}
