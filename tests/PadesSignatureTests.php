<?php

namespace Lacuna\RestPki\Tests;

use Lacuna\RestPki\PadesSignatureFinisher;
use Lacuna\RestPki\PadesSignatureFinisher2;
use Lacuna\RestPki\PadesSignatureStarter;
use Lacuna\RestPki\StandardSignaturePolicies;

class PadesSignatureTests extends BaseTest
{

    public function testPadesSimple()
    {
        $this->_testPadesSimple(false);
    }

    public function testPadesSimple_WP()
    {
        $this->_testPadesSimple(true);
    }

    private function _testPadesSimple($simulateWebPki)
    {
        // Perform signature

        $client = $this->getClient();

        $signatureStarter = new PadesSignatureStarter($client);
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::PADES_BASIC;
        $signatureStarter->securityContext = Config::LACUNA_TEST_SECURITY_CONTEXT;
        $signatureStarter->setPdfToSignFromPath(Config::TEST_PDF_PATH);
        $signatureFinisher = new PadesSignatureFinisher($client);

        $pdfBytes = $this->performSignature($signatureStarter, $signatureFinisher, $simulateWebPki);
        $signerCert = $signatureFinisher->getCertificateInfo();

        // Save signature in temp file

        $path = $this->saveInTempFile($pdfBytes);

        // Validate signature

        $this->checkPades($path, 1);
    }

    public function testPadesFinisher2()
    {
        $this->_testPadesFinisher2(false);
    }

    public function testPadesFinisher2_WP()
    {
        $this->_testPadesFinisher2(true);
    }

    private function _testPadesFinisher2($simulateWebPki)
    {
        // Perform signature

        $client = $this->getClient();

        $signatureStarter = new PadesSignatureStarter($client);
        $signatureStarter->signaturePolicy = StandardSignaturePolicies::PADES_BASIC;
        $signatureStarter->securityContext = Config::LACUNA_TEST_SECURITY_CONTEXT;
        $signatureStarter->setPdfToSignFromPath(Config::TEST_PDF_PATH);
        $signatureFinisher = new PadesSignatureFinisher2($client);

        $signatureResults = $this->performSignature2($signatureStarter, $signatureFinisher, $simulateWebPki);

        // Save signature in temp file

        $path = $this->getTempFilePath();
        $signatureResults->writeToFile($path);

        // Validate signature

        $this->checkPades($path, 1);
    }
}
