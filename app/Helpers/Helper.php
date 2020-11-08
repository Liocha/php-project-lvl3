<?php

namespace App\Helpers;

class Helper
{
    public static function getActivClass($currentRouteName, $linkRouteNane)
    {
        return $currentRouteName == $linkRouteNane ? 'active' : '';
    }
}
