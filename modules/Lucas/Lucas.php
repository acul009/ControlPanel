<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lucas
 *
 * @author acul
 */
class Lucas implements ControlPanelModule {

  private const SESSION_NAME = 'Lucas';

  public static function buildModuleMainPage(array $arrRequestParameters): string {
    return self::buildModuleSubPage($arrRequestParameters, '');
  }

  public static function buildModuleSubPage(array $arrRequestParameters, string $strSubPage): string {
    $strMainSite = '';
    switch ($strSubPage) {
      case 'Start Server':
        self::startServer();
        break;

      case 'command':
        self::sendCommandToServer($arrRequestParameters['command']);
        break;

      case 'console':
        echo self::getConsoleOutput();
        exit;

      default:
        $strMainSite = self::buildServerControl();
        break;
    }
    return $strMainSite;
  }

  public static function listSubPages(): array {
    return ['Control', 'Start Server'];
  }

  private static function buildServerControl(): string {
    $objTemplate = new TemplateFiller('serverControl', 'Lucas');
    return (string) $objTemplate;
  }

  private static function isServerOnline(): bool {
    return ScreenApi::sessionExists(self::SESSION_NAME);
  }

  private static function startServer(): void {
    $strServerPath = Initiator::active()->Library()->getWorkingDir() . '/modules/Lucas/Server/';
    $strJarPath = $strServerPath . 'server.jar';
    $strOutputPath = $strServerPath . 'output.txt';
    $strCommand = 'java -Xmx4G -Xms1G -jar ' . $strJarPath . ' nogui';
    ScreenApi::createSession($strServerPath, self::SESSION_NAME, $strCommand);
  }

  private static function sendCommandToServer(string $strCommand) {
    ScreenApi::sendCommandToSession($strCommand, self::SESSION_NAME);
  }

  private static function getConsoleOutput(): string {
    $strServerPath = Initiator::active()->Library()->getWorkingDir() . '/modules/Lucas/Server';
    $strFilePath = $strServerPath . '/logs/latest.log';
    $strOutput = file_exists($strFilePath) ? file_get_contents($strFilePath) : '';
    if (!self::isServerOnline()) {
      $strOutput .= "\n" . 'Server Offline';
    }
    return $strOutput;
  }

}
