<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StringTools
 *
 * @author acul
 */
class StringTools {

  public static function startsWith(string $strHaystack, string $strNeedle): bool {
    $intNeedleLength = count($strNeedle);
    $intHaystackLength = count($strHaystack);

    if ($intNeedleLength > $intHaystackLength) {
      return false;
    }

    $strCutToLength = substr($strHaystack, 0, $intNeedleLength);
    return $strCutToLength == $strNeedle;
  }

}
