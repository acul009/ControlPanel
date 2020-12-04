<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ScreenApi
 *
 * @author acul
 */
class ScreenApi {

  public static function getSessionList(string $strFilter = ''): array {
    $arrOutput = [];
    exec('screen -list', $arrOutput);
    $arrSessions = [];
    for ($i = 1; $i + 1 < count($arrOutput); ++$i) {
      $strSessionEntry = $arrOutput[$i];
      $intStart = strpos($strSessionEntry, '.') + 1;
      $intEnd = strpos($strSessionEntry, '(');
      $intLength = $intEnd - $intStart;
      $arrSessions[] = trim(substr($strSessionEntry, $intStart, $intLength));
    }
    return $arrSessions;
  }

  public static function createSession(string $strWorkingDir, string $strSessionName, string $strCommand = ''): void {
    if (!self::sessionExists($strSessionName)) {
      $arrOutput = [];
      $strScreenCommand = 'cd ' . $strWorkingDir . ' ; screen -dmS ' . $strSessionName . ' ' . $strCommand;
      exec($strScreenCommand, $arrOutput);
    }
  }

  public static function sessionExists(string $strSessionName): bool {
    $arrCandidates = self::getSessionList($strSessionName);
    return in_array($strSessionName, $arrCandidates);
  }

  public static function sendCommandToSession(string $strCommand, string $strSessionName): void {
    exec('screen -S ' . $strSessionName . ' -p 0 -X stuff \'' . $strCommand . '\\n\'');
  }

}
