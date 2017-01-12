<?php

namespace Lacuna\RestPki\Client;

class SignatureAlgorithmParameters
{

    /** @var string */
    public $token;

    /** @var string */
    public $toSignData;

    /** @var string */
    public $toSignHash;

    /** @var string */
    public $digestAlgorithmOid;

    /** @var string */
    public $openSslSignatureAlgorithm;
}