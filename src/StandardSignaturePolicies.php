<?php

namespace Lacuna\RestPki;

/**
 * Class StandardSignaturePolicies
 * @package Lacuna\RestPki
 */
class StandardSignaturePolicies
{
    const PADES_BASIC = '78d20b33-014d-440e-ad07-929f05d00cdf';
    const PADES_BASIC_WITH_ICPBR_CERTS = '3fec800c-366c-49bf-82c5-2e72154e70f6';
    const PADES_BASIC_WITHOUT_LTV = 'ed93a777-ad5a-49a0-8b67-5aa5dfad1c98';
    const PADES_T_WITH_ICPBR_CERTS = '6a39aeea-a2d0-4754-bf8c-19da15296ddb';
    const PADES_ICPBR_ADR_BASICA = '531d5012-4c0d-4b6f-89e8-ebdcc605d7c2';
    const PADES_ICPBR_ADR_TEMPO = '10f0d9a5-a0a9-42e9-9523-e181ce05a25b';
    const PADES_ADOBE_READER = 'aaed831b-fa9d-4732-82fc-8d25f2c16c57';
    const CADES_BES = 'a4522485-c9e5-46c3-950b-0d6e951e17d1';
    const CADES_ICPBR_ADR_BASICA = '3ddd8001-1672-4eb5-a4a2-6e32b17ddc46';
    const CADES_ICPBR_ADR_BASICA_WITHOUT_CRLS = 'd890b391-f82f-468c-a7e8-bf734dde9d6e';
    const CADES_ICPBR_ADR_TEMPO = 'a5332ad1-d105-447c-a4bb-b5d02177e439';
//    const CADES_ICPBR_ADR_VALIDACAO = '92378630-dddf-45eb-8296-8fee0b73d5bb';
    const CADES_ICPBR_ADR_COMPLETA = '30d881e7-924a-4a14-b5cc-d5a1717d92f6';
    const CADES_BES_WITH_SIGNING_TIME_AND_NO_CRLS = '8108539d-c137-4f45-a1f2-de5305bc0a37';
    const XML_XADES_BES = '1beba282-d1b6-4458-8e46-bd8ad6800b54';
    const XML_DSIG_BASIC = '2bb5d8c9-49ba-4c62-8104-8141f6459d08';
    const XML_ICPBR_NFE_PADRAO_NACIONAL = 'a3c24251-d43a-4ba4-b25d-ee8e2ab24f06';
    const XML_ICPBR_ADR_BASICA = '1cf5db62-58b6-40ba-88a3-d41bada9b621';
    const XML_ICPBR_ADR_TEMPO = '5aa2e0af-5269-43b0-8d45-f4ef52921f04';
    const XML_ICPBR_ADR_ARQUIVAMENTO = '5b8c0710-006d-434d-bf35-12292b56b90a';
    const XML_ICPBR_ADR_COMPLETA = '826b3a00-3400-4236-90d9-40917b0cc83b';
    const XML_COD_SHA1 = 'bf71e0fc-6ffd-4135-a137-0e488a3ad39e';
    const XML_COD_SHA256 = '45d83a40-1d59-480a-8cd2-d3df1060cce3';
}
