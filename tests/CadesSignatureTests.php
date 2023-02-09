<?php

namespace Lacuna\RestPki\Tests;

use Lacuna\RestPki\CadesSignatureFinisher;
use Lacuna\RestPki\CadesSignatureFinisher2;
use Lacuna\RestPki\CadesSignatureStarter;
use Lacuna\RestPki\StandardSignaturePolicies;

class CadesSignatureTests extends BaseTest
{
    #region CAdES simple

    public function testCadesSimple()
    {
        $this->_testCadesSimple(false);
    }

    public function testCadesSimple_WP()
    {
        $this->_testCadesSimple(true);
    }

    private function _testCadesSimple($simulateWebPki)
    {
        // Perform signature

        $client = $this->getClient();

        $signatureStarter = new CadesSignatureStarter($client);
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::CADES_ICPBR_ADR_BASICA;
        $signatureStarter->securityContext = Config::LACUNA_TEST_SECURITY_CONTEXT;
        $signatureStarter->setFileToSignFromPath(Config::TEST_PDF_PATH);
        $signatureFinisher = new CadesSignatureFinisher2($client);

        $signatureResult = $this->performSignature($signatureStarter, $signatureFinisher, $simulateWebPki);
        $file = $signatureResult->getContentRaw();

        // Save signature in temp file

        $path = $this->saveInTempFile($file);

        // Validate signature

        $this->checkCades($path, 1);
    }

    #endregion

    #region CAdES with Finisher2

    public function testCadesFinisher2()
    {
        $this->_testCadesFinisher2(false);
    }

    public function testCadesFinisher2_WP()
    {
        $this->_testCadesFinisher2(true);
    }

    private function _testCadesFinisher2($simulateWebPki)
    {
        // Perform signature

        $client = $this->getClient();

        $signatureStarter = new CadesSignatureStarter($client);
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::CADES_ICPBR_ADR_BASICA;
        $signatureStarter->securityContext = Config::LACUNA_TEST_SECURITY_CONTEXT;
        $signatureStarter->setFileToSignFromPath(Config::TEST_PDF_PATH);
        $signatureFinisher = new CadesSignatureFinisher2($client);

        $signatureResults = $this->performSignature2($signatureStarter, $signatureFinisher, $simulateWebPki);

        // Save signature in temp file

        $path = $this->getTempFilePath();
        $signatureResults->writeToFile($path);

        // Validate signature

        $this->checkCades($path, 1);
    }

    #endregion

    #region CAdES sign and co-sign from result

    public function testCadesSignAndCoSignFromResult()
    {
        $this->_testCadesSignAndCoSignFromResult(false);
    }

    public function testCadesSignAndCoSignFromResult_WP()
    {
        $this->_testCadesSignAndCoSignFromResult(true);
    }

    private function _testCadesSignAndCoSignFromResult($simulateWebPki)
    {
        $client = $this->getClient();

        // First signature

        $firstSignatureStarter = new CadesSignatureStarter($client);
        $firstSignatureStarter->signaturePolicy = StandardSignaturePolicies::CADES_ICPBR_ADR_BASICA;
        $firstSignatureStarter->securityContext = Config::LACUNA_TEST_SECURITY_CONTEXT;
        $firstSignatureStarter->setFileToSignFromPath(Config::TEST_PDF_PATH);
        $firstSignatureFinisher = new CadesSignatureFinisher2($client);
        $firstSignatureFinisher->forceBlobResult = true;

        $firstResult = $this->performSignature2($firstSignatureStarter, $firstSignatureFinisher, $simulateWebPki);
        
        // Check that upload was used

        $this->assertTrue($client->_uploadCount == 1);

        // Second signature

        $secondSignatureStarter = new CadesSignatureStarter($client);
        $secondSignatureStarter->signaturePolicy = StandardSignaturePolicies::CADES_ICPBR_ADR_BASICA;
        $secondSignatureStarter->securityContext = Config::LACUNA_TEST_SECURITY_CONTEXT;
        $secondSignatureStarter->setCmsToCoSignFromResult($firstResult);

        $secondResult = $this->performSignature2($secondSignatureStarter, new CadesSignatureFinisher2($client), $simulateWebPki);

        // Check that the file was not uploaded again

        $this->assertTrue($client->_uploadCount == 1);

        // Save signature in temp file

        $path = $this->getTempFilePath();
        $secondResult->writeToFile($path);

        // Validate signature

        $this->checkCades($path, 2);
    }

    #endregion
}
