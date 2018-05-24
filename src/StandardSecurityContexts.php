<?php

namespace Lacuna\RestPki;

/**
 * Class StandardSecurityContexts
 * @package Lacuna\RestPki
 */
class StandardSecurityContexts
{
    const PKI_BRAZIL = '201856ce-273c-4058-a872-8937bd547d36';
    const PKI_ITALY = 'c438b17e-4862-446b-86ad-6f85734f0bfe';
    const WINDOWS_SERVER = '3881384c-a54d-45c5-bbe9-976b674f5ec7';

    /**
     * Lacuna Test PKI (use for development purposes only!) If you are using an on-premises instance of Rest PKI
     * (instead of https://pki.rest/), this security context might have to be created first.
     */
    const LACUNA_TEST = '803517ad-3bbc-4169-b085-60053a8f6dbf';
}
