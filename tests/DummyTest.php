<?php

namespace Lacuna\RestPki\Tests;

use Lacuna\RestPki\Authentication;
use PHPUnit_Framework_TestCase;

class DummyTest extends PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $client = Util::getClient();
        $auth = new Authentication($client);
        $token = $auth->startWithWebPki(Util::LACUNA_TEST_SECURITY_CONTEXT);
        $this->assertTrue(!empty($token));
    }
}
