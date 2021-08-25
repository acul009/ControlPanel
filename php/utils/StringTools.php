<?php

declare(strict_types=1);

namespace utils;

class StringTools {

    public static function startsWith(string $strHaystack, string $strNeedle): bool {
        $intNeedleLength = strlen($strNeedle);
        $intHaystackLength = strlen($strHaystack);

        if ($intNeedleLength > $intHaystackLength) {
            return false;
        }

        $strCutToLength = substr($strHaystack, 0, $intNeedleLength);
        return $strCutToLength == $strNeedle;
    }

    public static function containsForbiddenSequence(string $strHaystack, array $arrForbiddenSequences): bool {
        foreach ($arrForbiddenSequences as $strForbiddenSequence) {
            if (strpos($strHaystack, $strForbiddenSequence) !== false) {
                return false;
            }
        }
        return true;
    }

}
