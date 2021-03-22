<?php


namespace Lacuna\RestPki;


class CertificateExplorerResult
{
    public $certificate;
    public $validationResults;

    public function __construct($model)
    {
        if (isset($model)) {
            if (isset($model->certificate)) {
                $this->certificate = new PKCertificate($model->certificate);
            }
            if (isset($model->validationResults)) {
                $this->validationResults = new ValidationResults($model->validationResults);
            }
        }
    }


}