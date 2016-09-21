<?php

namespace Lacuna\RestPki\Client;

class PadesVisualPositioningPresets
{

    private static $cachedPresets = array();

    public static function getFootnote(RestPkiClient $restPkiClient, $pageNumber = null, $rows = null)
    {
        $urlSegment = 'Footnote';
        if (!empty($pageNumber)) {
            $urlSegment .= "?pageNumber=" . $pageNumber;
        }
        if (!empty($rows)) {
            $urlSegment .= "?rows=" . $rows;
        }
        return self::getPreset($restPkiClient, $urlSegment);
    }

    public static function getNewPage(RestPkiClient $restPkiClient)
    {
        return self::getPreset($restPkiClient, 'NewPage');
    }

    private static function getPreset(RestPkiClient $restPkiClient, $urlSegment)
    {
        if (array_key_exists($urlSegment, self::$cachedPresets)) {
            return self::$cachedPresets[$urlSegment];
        }
        $preset = $restPkiClient->get("Api/PadesVisualPositioningPresets/$urlSegment");
        self::$cachedPresets[$urlSegment] = $preset;
        return $preset;
    }
}