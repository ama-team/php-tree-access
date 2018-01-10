<?php

namespace AmaTeam\TreeAccess\Misc;

class Strings
{
    /**
     * @param string $subject
     * @param string $prefix
     * @return string|false
     */
    public static function stripCamelCasePrefix($subject, $prefix)
    {
        if ($prefix === $subject) {
            return '';
        }
        if (strpos($subject, $prefix) !== 0) {
            return false;
        }
        $cut = substr($subject, strlen($prefix));
        return strtolower($cut[0]) . substr($cut, 1);
    }
}
