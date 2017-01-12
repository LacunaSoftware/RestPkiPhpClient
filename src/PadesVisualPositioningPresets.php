<?php

namespace Lacuna\RestPki\Client;

/**
 * Class PadesVisualPositioningPresets
 * @package Lacuna\RestPki\Client
 */
class PadesVisualPositioningPresets
{
    private static $cachedPresets = array();

    /**
     * @param RestPkiClient $client
     * @param int|null $pageNumber
     * @param int|null $rows
     * @return mixed
     */
    public static function getFootnote($client, $pageNumber = null, $rows = null)
    {
        $urlSegment = 'Footnote';
        if (!empty($pageNumber)) {
            $urlSegment .= "?pageNumber=" . $pageNumber;
        }
        if (!empty($rows)) {
            $urlSegment .= "?rows=" . $rows;
        }
        return self::getPreset($client, $urlSegment);
    }

    /**
     * @param RestPkiClient $client
     * @return mixed
     */
    public static function getNewPage($client)
    {
        return self::getPreset($client, 'NewPage');
    }

    /**
     * @param RestPkiClient $restPkiClient
     * @param string $urlSegment
     * @return mixed
     */
    private static function getPreset($restPkiClient, $urlSegment)
    {
        if (array_key_exists($urlSegment, self::$cachedPresets)) {
            return self::$cachedPresets[$urlSegment];
        }
        $preset = $restPkiClient->get("Api/PadesVisualPositioningPresets/$urlSegment");
        self::$cachedPresets[$urlSegment] = $preset;
        return $preset;
    }
}
