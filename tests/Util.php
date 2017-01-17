<?php

namespace Lacuna\RestPki\Tests;

use Lacuna\RestPki\RestPkiClient;

class Util
{
    const ENDPOINT = 'http://pki.rest/';
    const TOKEN = '>>> API TOKEN HERE <<<';
    const LACUNA_TEST_SECURITY_CONTEXT = '803517ad-3bbc-4169-b085-60053a8f6dbf';

    static function getClient()
    {
        return new RestPkiClient(self::ENDPOINT, self::TOKEN);
    }
}
