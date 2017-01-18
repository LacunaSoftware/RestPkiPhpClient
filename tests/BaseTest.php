<?php

namespace Lacuna\RestPki\Tests;

use PHPUnit_Framework_TestCase;
use Lacuna\RestPki\RestPkiClient;
use Lacuna\RestPki\SignatureFinisher;
use Lacuna\RestPki\SignatureStarter;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    protected function getClient()
    {
        return new RestPkiClient(Config::ENDPOINT, Config::TOKEN);
    }

    protected function getClientAnonymous()
    {
        return new RestPkiClient(Config::ENDPOINT, null);
    }

    protected function getTestCertificateRaw()
    {
        return base64_decode(Config::TEST_CERT_BASE64);
    }

    protected function getTestCertificateBase64()
    {
        return Config::TEST_CERT_BASE64;
    }

    protected function runPkiUtil($commandName, $commandArgs)
    {
        $cmd = __DIR__ . '\\bin\\PkiUtil\\PkiUtil.exe ' . $commandName . ' ' . implode(' ', $commandArgs);
        $output = [];
        $returnCode = -1;
        exec($cmd, $output, $returnCode);

        if ($returnCode != 0) {
            $this->fail("PkiUtil.exe returned code $returnCode: " . implode('\r\n', $output));
        }

        return $output;
    }

    /**
     * @param SignatureStarter $signatureStarter
     * @param SignatureFinisher $signatureFinisher
     * @param bool $simulateWebPki
     * @return string
     */
    protected function performSignature($signatureStarter, $signatureFinisher, $simulateWebPki)
    {
        if ($simulateWebPki) {

            // Start signature

            $token = $signatureStarter->startWithWebPki();

            // Compute signature as Web PKI would

            $this->signAsWebPki($token);

            // Set finisher parameters

            $signatureFinisher->token = $token;

        } else {

            // Start signature

            $signatureStarter->setSignerCertificateBase64(self::getTestCertificateBase64());
            $signatureParams = $signatureStarter->start();

            // Compute signature

            $signatureRaw = $this->signWithTestCertificate($signatureParams->toSignHash, $signatureParams->digestAlgorithmOid);

            // Set finisher parameters

            $signatureFinisher->token = $signatureParams->token;
            $signatureFinisher->setSignatureRaw($signatureRaw);

        }

        // Complete signature

        return $signatureFinisher->finish();
    }

    /**
     * @param string $token
     * @throws \Lacuna\RestPki\RestUnreachableException
     */
    protected function signAsWebPki($token)
    {
        $client = $this->getClientAnonymous();

        // Post certificate and get signature parameters

        $response = $client->post("Api/PendingSignatures/$token/Certificate", [
            'certificate' => $this->getTestCertificateBase64()
        ]);
        $toSignHashRaw = base64_decode($response->toSignHash);
        $digestAlg = $response->digestAlgorithmOid;

        // Compute signature

        $signatureRaw = $this->signWithTestCertificate($toSignHashRaw, $digestAlg);

        // Post signature

        $client->post("Api/PendingSignatures/$token", [
            'signature' => base64_encode($signatureRaw)
        ]);
    }

    /**
     * @param string $toSignHashRaw
     * @param string $digestAlg
     * @return string
     */
    protected function signWithTestCertificate($toSignHashRaw, $digestAlg)
    {
        $certThumbBase64 = base64_encode(hash('sha256', $this->getTestCertificateRaw(), true));
        $toSignHashBase64 = base64_encode($toSignHashRaw);

        $output = $this->runPkiUtil('signHash', [$certThumbBase64, $toSignHashBase64, $digestAlg]);

        return base64_decode($output[0]);
    }

    protected function checkPades($path, $expectedSignerCount)
    {
        $this->runPkiUtil('checkPades', [$path, $expectedSignerCount]);
    }

    protected function getTempFilePath()
    {
        return tempnam(sys_get_temp_dir(), 'Rpt');
    }

    protected function saveInTempFile($contentRaw)
    {
        $path = $this->getTempFilePath();
        file_put_contents($path, $contentRaw);
        return $path;
    }
}
