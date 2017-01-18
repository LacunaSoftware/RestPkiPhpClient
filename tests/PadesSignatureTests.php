<?php

namespace Lacuna\RestPki\Tests;

use Lacuna\RestPki\PadesSignatureFinisher;
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

        // Check signature

        $this->checkPades($path, 1);
    }
}
