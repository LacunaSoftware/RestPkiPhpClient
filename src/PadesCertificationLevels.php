<?php

namespace Lacuna\RestPki;

/**
 * Class PadesCertificationLevels
 * @package Lacuna\RestPki
 */
class PadesCertificationLevels
{
    const NOT_CERTIFIED = 'NotCertified';
    const CERTIFIED_FORM_FILLING = 'CertifiedFormFilling';
    const CERTIFIED_FORM_FILLING_AND_ANNOTATIONS = 'CertifiedFormFillingAndAnnotations';
    const CERTIFIED_NO_CHANGES_ALLOWED = 'CertifiedNoChangesAllowed';
}
