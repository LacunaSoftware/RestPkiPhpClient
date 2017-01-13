<?php

namespace Lacuna\RestPki;

/**
 * Class Color
 * @package Lacuna\RestPki
 *
 * @property int $alpha
 * @property int $blue
 * @property int $green
 * @property int $red
 */
class Color
{
    public $alpha;
    public $blue;
    public $green;
    public $red;

    public function __construct()
    {
        $args = func_get_args();
        if (sizeof($args) == 1 && is_string($args[0]) && strlen($args[0]) == 7) { // Case "#RRGGBB"
            $this->red = hexdec(substr($args[0], 1, 2));
            $this->green = hexdec(substr($args[0], 3, 2));
            $this->blue = hexdec(substr($args[0], 5, 2));
            $this->alpha = 100;
        } else {
            if (sizeof($args) == 2 && is_string($args[0]) && strlen($args[0]) == 7) { // Case ("#RRGGBB", a)
                $this->red = hexdec(substr($args[0], 1, 2));
                $this->green = hexdec(substr($args[0], 3, 2));
                $this->blue = hexdec(substr($args[0], 5, 2));
                $this->alpha = $args[1];
            } else {
                if (sizeof($args) == 3) { // Case (r, g, b)
                    $this->red = $args[0];
                    $this->green = $args[1];
                    $this->blue = $args[2];
                    $this->alpha = 100;
                } else {
                    if (sizeof($args) == 4) { // Case (r, g, b, a)
                        $this->red = $args[0];
                        $this->green = $args[1];
                        $this->blue = $args[2];
                        $this->alpha = $args[3];
                    } else {
                        throw new \InvalidArgumentException("Invalid parameters passed to the Color's constructor.");
                    }
                }
            }
        }

        if (!is_int($this->red) || !is_int($this->green) || !is_int($this->blue) || (isset($this->alpha) && !is_int($this->alpha))) {
            throw new \InvalidArgumentException("Invalid parameters passed to the Color's constructor.");
        }
    }
}
