<?php

namespace Lacuna\RestPki;

/**
 * Class StandardSignaturePolicyCatalog
 * @package Lacuna\RestPki
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

    public static function getPkiBrazilXades()
    {
        return array(
            StandardSignaturePolicies::XML_ICPBR_ADR_BASICA,
            StandardSignaturePolicies::XML_ICPBR_ADR_TEMPO
        );
    }
}
