<?php

namespace Lacuna\RestPki;

/**
 * Class PadesVisualPositioningPresets
 * @package Lacuna\RestPki
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
        // casting to int so the value isn't interpreted as false (bool) when it's 0
        $pageNumber = (int) $pageNumber; 
        $urlSegment = 'Footnote';
        if (!empty($pageNumber) || $pageNumber == 0) {
            $urlSegment .= "?pageNumber=" . $pageNumber;
        }
        if (!empty($rows)) {
            $urlSegment .= (!empty($pageNumber) || $pageNumber == 0 ) ? "&" : "?";
            $urlSegment .= "rows=" . $rows;
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
