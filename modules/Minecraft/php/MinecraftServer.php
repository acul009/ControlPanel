<?php

/**
 * Description of Server
 *
 * @author acul
 */
class MinecraftServer {

  private string $strName;
  private string $strVersion;

  private MinecraftWorld $objWorld;

  private const CONFIG_FILENAME = 'controlPanel.conf';

  private function __construct() {

  }

  public static function getServerByName(string $strServerName): MinecraftServer {
    return new static(Minecraft::SERVER_DIRECTORY . '/' .$strServerName);
  }

  public static function getServerList(): array {
    $arrServerNames = glob(Minecraft::SERVER_DIRECTORY . '/*', GLOB_ONLYDIR);
    $arrServerList = [];
    foreach ($arrServerNames as $strServerName) {
      $arrServerList[] = static::getServerByName($strServerName);
    }
    return $arrServerList;
  }

}
