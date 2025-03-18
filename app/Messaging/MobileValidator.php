<?php

namespace App\Messaging;

class MobileValidator
{
    public static function isLocalNigerianGSMNumber($number) {
        // List of valid Nigerian GSM prefixes
        $validPrefixes = ['080', '081', '090', '070', '091'];

        // Check if the number is exactly 11 digits and starts with a valid prefix
        if (strlen($number) == 11 && in_array(substr($number, 0, 3), $validPrefixes)) {
            return true;
        }

        return false;
    }

    public static function formatNigerianNumberToInternationalFormat($number, $addPlusPrefix = false)
    {
        $plusPrefix = $addPlusPrefix ? '+' : '';
        return self::isLocalNigerianGSMNumber($number) ? $plusPrefix . '234'.substr($number, 1) : $number;
    }
}