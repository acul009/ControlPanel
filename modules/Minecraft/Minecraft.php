<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Minecraft
 *
 * @author timon
 */
class Minecraft implements ControlPanelModule {

  //put your code here
  public static function buildModuleMainPage(array $arrRequestParameters): string {
    return self::buildModuleSubPage($arrRequestParameters, 'Server');
  }

  public static function buildModuleSubPage(array $arrRequestParameters, string $strSubPage): string {
    $strPageHTML = '';
    switch ($strSubPage) {

      case 'Server':
        $strPageHTML = self::buildServerList($arrRequestParameters);
        break;

      case 'Worlds':
        $strPageHTML = self::buildWorldList($arrRequestParameters);

        break;
      case 'NewServer':
        $strPageHTML = self::buildNewServer($arrRequestParameters);

        break;
    }


    return $strPageHTML;
  }

  public static function listSubPages(): array {
    return ['Server', 'Worlds'];
  }

  private static function buildServerList(array $arrRequestParameters): string {
    $strServerDir = Initiator::active()->Library()->getWorkingDir() . '/modules/Minecraft/Server';
    $objServerDir = opendir($strServerDir);
    $objPreviewTemp = new TemplateFiller('TempServerPreview', 'Minecraft');
    $strPreviewList = '';

    while ($strServerName = readdir($objServerDir)) {
      if ($strServerName != '.' && $strServerName != '..') {
        $objPreviewTemp->setSubstituteArray(['Servername' => $strServerName]);
        $strPreviewList .= $objPreviewTemp;
      }
    }


    $objListTemplate = new TemplateFiller('TempServerList', 'Minecraft');
    $objListTemplate
            ->setSubstituteArray(['Server' => $strPreviewList]);
    return (string) $objListTemplate;
  }

  private static function buildWorldList(array $arrRequestParameters): string {

  }

  private static function buildNewServer(array $arrRequestParameters): string {
    $strServerDir = Initiator::active()->Library()->getWorkingDir() . '/modules/Minecraft/Server';
    $objServerDir = opendir($strServerDir);
    $intNumber = 1;
    $strServerPrefix = 'Server';



    while ($strServerName = readdir($objServerDir)) {
      if ($strServerName != '.' && $strServerName != '..') {
        if (StringTools::startsWith($strServerName, $strServerPrefix)) {
          $intServerLength = strlen($strServerPrefix);
          $strNumber = substr($strServerName, $intServerLength);
          $intServerNum = intval($strNumber);

          if ($intServerNum >= $intNumber) {
            $intNumber = $intServerNum + 1;
          }
        }
      }
    }$strFinalServerName = $strServerPrefix . $intNumber;
    mkdir($strServerDir . '/' . $strFinalServerName);


    return '';
  }

}
