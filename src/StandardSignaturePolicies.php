<?php

namespace Lacuna\RestPki\Client;

class StandardSignaturePolicies
{
    const PADES_BASIC = '78d20b33-014d-440e-ad07-929f05d00cdf';
    const PADES_BASIC_WITH_ICPBR_CERTS = '3fec800c-366c-49bf-82c5-2e72154e70f6';
    const PADES_T_WITH_ICPBR_CERTS = '6a39aeea-a2d0-4754-bf8c-19da15296ddb';
    const PADES_ICPBR_ADR_BASICA = '531d5012-4c0d-4b6f-89e8-ebdcc605d7c2';
    const PADES_ICPBR_ADR_TEMPO = '10f0d9a5-a0a9-42e9-9523-e181ce05a25b';
    const CADES_BES = 'a4522485-c9e5-46c3-950b-0d6e951e17d1';
    const CADES_ICPBR_ADR_BASICA = '3ddd8001-1672-4eb5-a4a2-6e32b17ddc46';
    const CADES_ICPBR_ADR_TEMPO = 'a5332ad1-d105-447c-a4bb-b5d02177e439';
    const CADES_ICPBR_ADR_VALIDACAO = '92378630-dddf-45eb-8296-8fee0b73d5bb';
    const CADES_ICPBR_ADR_COMPLETA = '30d881e7-924a-4a14-b5cc-d5a1717d92f6';
    const XML_XADES_BES = '1beba282-d1b6-4458-8e46-bd8ad6800b54';
    const XML_DSIG_BASIC = '2bb5d8c9-49ba-4c62-8104-8141f6459d08';
    const XML_ICPBR_NFE_PADRAO_NACIONAL = 'a3c24251-d43a-4ba4-b25d-ee8e2ab24f06';
    const XML_ICPBR_ADR_BASICA = '1cf5db62-58b6-40ba-88a3-d41bada9b621';
    const XML_ICPBR_ADR_TEMPO = '5aa2e0af-5269-43b0-8d45-f4ef52921f04';
}