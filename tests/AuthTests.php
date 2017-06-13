<?php

namespace Lacuna\RestPki\Tests;

use Lacuna\RestPki\Authentication;

class AuthTests extends BaseTest
{
    public function testAuthSimple()
    {
        $client = $this->getClient();
        $auth = new Authentication($client);
        $token = $auth->startWithWebPki(Config::LACUNA_TEST_SECURITY_CONTEXT);
        $this->signAsWebPki($token);
        $vr = $auth->completeWithWebPki($token);
        $this->assertTrue($vr->isValid());
    }
}
