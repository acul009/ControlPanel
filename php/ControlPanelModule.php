<?php

/**
 *
 * @author acul
 */
interface ControlPanelModule {

  public static function buildModuleMainPage(array $arrRequestParameters): string;

  public static function buildModuleSubPage(array $arrRequestParameters, string $strSubPage): string;

  public static function listSubPages(): array;
}
