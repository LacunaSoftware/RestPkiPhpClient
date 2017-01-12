<?php

namespace Lacuna\RestPki\Client;

/**
 * Class StandardSignaturePolicyCatalog
 * @package Lacuna\RestPki\Client
 */
class StandardSignaturePolicyCatalog
{
    public static function getPkiBrazilCades()
    {
        return array(
            StandardSignaturePolicies::CADES_ICPBR_ADR_BASICA,
            StandardSignaturePolicies::CADES_ICPBR_ADR_TEMPO,
            StandardSignaturePolicies::CADES_ICPBR_ADR_COMPLETA
        );
    }

    public static function getPkiBrazilCadesWithSignerCertificateProtection()
    {
        return array(
            StandardSignaturePolicies::CADES_ICPBR_ADR_TEMPO,
            StandardSignaturePolicies::CADES_ICPBR_ADR_COMPLETA
        );
    }

    public static function getPkiBrazilCadesWithCACertificateProtection()
    {
        return array(
            StandardSignaturePolicies::CADES_ICPBR_ADR_COMPLETA
        );
    }

    public static function getPkiBrazilPades()
    {
        return array(
            StandardSignaturePolicies::PADES_ICPBR_ADR_BASICA,
            StandardSignaturePolicies::PADES_ICPBR_ADR_TEMPO
        );
    }

    public static function getPkiBrazilPadesWithSignerCertificateProtection()
    {
        return array(
            StandardSignaturePolicies::PADES_ICPBR_ADR_TEMPO
        );
    }
}
