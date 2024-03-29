<?php

declare(strict_types=1);
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
class Minecraft implements ControlPanelModuleInterface {

    private static TemplateFiller $objServerBlockFiller;

    public const SERVER_DIRECTORY = '/modules/Minecraft/data/Server';
    public const VERSIONS_DIRECTORY = '/modules/Minecraft/data/Versions';

//put your code here
    public static function buildModuleMainPage(array $arrRequestParameters): string {
        return self::buildModuleSubPage($arrRequestParameters, 'Server');
    }

    public static function buildModuleSubPage(array $arrRequestParameters, string $strSubPage): string {
        self:: createServerDirWhenMissing();

        $strPageHTML = '';
        switch ($strSubPage) {

            case 'Server':
                $strPageHTML = self::buildServerList($arrRequestParameters);
                break;

            case 'Worlds':
                $strPageHTML = self::buildWorldList($arrRequestParameters);
                break;

            case 'Versions':
                $strPageHTML = self::buildVersionsPage($arrRequestParameters);
                break;

            case 'NewServer':
                $strPageHTML = self::buildNewServer($arrRequestParameters);

                break;

            case 'config':
                $strPageHTML = self::buildConfig($arrRequestParameters);

                break;
        }


        return $strPageHTML;
    }

    public static function listSubPages(): array {
        return ['Server', 'Worlds', 'Versions'];
    }

    private static function buildVersionsPage(array $arrRequestParameters): string {
        
    }

    private static function buildServerList(array $arrRequestParameters): string {
        $strServerDir = Initiator::active()->Library()->getWorkingDir() . self::SERVER_DIRECTORY;
        $objServerDir = opendir($strServerDir);
        $objPreviewTemp = new TemplateFiller('TempServerPreview', 'Minecraft');
        $strPreviewList = '';

        while ($strServerName = readdir($objServerDir)) {
            if ($strServerName != '.' && $strServerName != '..') {
                $strPreviewList .= self::buildServerBlock($strServerName);
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
        $strServerDir = Initiator::active()->Library()->getWorkingDir() . self::SERVER_DIRECTORY;
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
        $strServerBlock = self::buildServerBlock($strFinalServerName);
        exit($strServerBlock);
        return '';
    }

    public static function buildServerBlock(string $strServerName): string {
        if (!isset(self::$objServerBlockFiller)) {
            self::$objServerBlockFiller = new TemplateFiller('TempServerPreview', 'Minecraft');
        }

        self::$objServerBlockFiller->setSubstituteArray(['Servername' => $strServerName]);
        return (string) self::$objServerBlockFiller;
    }

    private static function buildConfig(array $arrRequestParameters): string {
        $objConfigTemp = New TemplateFiller('ServerConfig', 'Minecraft');
        $objConfigTemp->setSubstituteArray([]);
        return (string) $objConfigTemp;
    }

    private static function createServerDirWhenMissing(): void {
        $strDirPath = Initiator::active()->Library()->getWorkingDir() . self::SERVER_DIRECTORY;
        if (!file_exists($strDirPath)) {
            mkdir($strDirPath, true);
        }
    }

}
