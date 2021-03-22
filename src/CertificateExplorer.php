<?php


namespace Lacuna\RestPki;


class CertificateExplorer
{
    public $securityContextId;
    public $validate;

    /** @var RestPkiClient */
    private $client;
    private $certificateBase64;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Sets the raw (binary) contents of the certificate
     *
     * @param string $certificate The binary contents of the certificate
     */
    public function setCertificateRaw($certificate)
    {
        $this->certificateBase64 = base64_encode($certificate);
    }

    /**
     * @deprecated Use function setCertificateRaw
     *
     * @param string $certificate The raw (binary) contents of the certificate
     */
    public function setCertificate($certificate)
    {
        $this->setCertificateRaw($certificate);
    }

    /**
     * Sets the base64-encoded contents of the certificate
     *
     * @param string $certificate The base64-encoded contents of the certificate
     */
    public function setCertificateBase64($certificate)
    {
        $this->certificateBase64 = $certificate;
    }

    public function open()
    {
        if (empty($this->certificateBase64)) {
            throw new \LogicException("The certificate file was not set");
        }
        $request = $this->getRequest();
        $response = $this->client->post('Api/Certificates/Open', $request);
        return new CertificateExplorerResult($response);
    }

    private function getRequest()
    {
        return array(
            'certificate'       => $this->certificateBase64,
            'securityContextId' => $this->securityContextId,
            'validate'          => $this->validate,
        );
    }
}