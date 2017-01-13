<?php

namespace Lacuna\RestPki;

/**
 * Class SignatureAlgorithmParameters
 * @package Lacuna\RestPki
 *
 * @property string $token The token that identifies this signature process on Rest PKI
 * @property string $toSignData The binary encoded "to-sign-bytes", to be used as input to the signature algorithm
 * @property string $toSignHash The binary encoded "to-sign-hash" (digest of the "to-sign-bytes"), can alternatively be used as input to the signature algorithm
 * @property string $digestAlgorithmOid The OID of the digest algorithm to be used during the signature computation
 * @property string $openSslSignatureAlgorithm The name of the signature algorithm, as expected by the OpenSSL PHP functions
 */
class SignatureAlgorithmParameters
{
    public $token;
    public $toSignData;
    public $toSignHash;
    public $digestAlgorithmOid;
    public $openSslSignatureAlgorithm;
}
