<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core;

use Initiator;

class LibraryManagerLegacy {

    private string $strWorkingDir;
    private array $arrModuleNames;
    private array $arrAutoloadLocations = ['/php' => true];

    private const PHP_DIR = 'php';
    private const TEMPLATE_DIR = 'templates';
    private const CSS_DIR = 'css';
    private const JAVASCRIPT_DIR = 'javascript';
    private const PHP_EXT = 'php';
    private const TEMPLATE_EXT = 'html';
    private const CSS_EXT = 'css';
    private const JAVASCRIPT_EXT = 'js';
    private const CSS_HEADER = 'text/css';
    private const JAVASCRIPT_HEADER = 'text/javascript';

    public function __construct() {
        $this->updateWorkingDir();
        //$this->loadAllBaseFiles();
        spl_autoload_register([$this, 'tryLoadClass']);
        $this->indexModules();
    }

    private function updateWorkingDir(): void {
        $this->strWorkingDir = dirname(getcwd(), 1);
    }

    private function loadAllBaseFiles(): void {
        foreach (glob($this->getWorkingDir() . "/php/*.php") as $filename) {
            include_once $filename;
        }
    }

    private function tryLoadClass($strClass) {
        foreach (array_keys($this->arrAutoloadLocations) as $strLocation) {
            $strClass = str_replace('\\', '/', $strClass);
            $strFilename = $this->getWorkingDir() . $strLocation . '/' . $strClass . '.php';
            if (file_exists($strFilename)) {
                include_once $strFilename;
                return;
            }
        }
    }

    private function addAutoLoadLocation(string $strLocation) {
        if (!isset($this->arrAutoloadLocations[$strLocation])) {
            $this->arrAutoloadLocations[$strLocation] = true;
        }
    }

    private function indexModules(): void {
        $strModuleDir = $this->strWorkingDir . '/modules';

        $this->arrModuleNames = [];

        $objModuleDirectory = opendir($strModuleDir);

        while ($objModuleName = readdir($objModuleDirectory)) {
            if ($objModuleName != '.' && $objModuleName != '..') {
                $this->arrModuleNames[] = $objModuleName;
            }
        }
    }

    public function listModules(bool $listAll = false): array {
        $arrModuleList = [];
        foreach ($this->arrModuleNames as $strModule) {
            if ($listAll || Initiator::active()->Authentication()->activeUser()->mayUseModule($strModule)) {
                $arrModuleList[] = $strModule;
            }
        }
        return $arrModuleList;
    }

    public function getWorkingDir() {
        return $this->strWorkingDir;
    }

    public function runModule(string $strModule) {
        if (Initiator::active()->Authentication()->activeUser()->mayUseModule($strModule)) {
            $strModuleFileName = str_replace(' ', '_', $strModule);
            $strModulePath = self::getWorkingDir() . '/modules/' . $strModule . '/';
            $strModuleFilePath = $strModulePath . $strModuleFileName . '.php';
            if (file_exists($strModuleFilePath)) {
                include_once $strModuleFilePath;
                $this->addAutoLoadLocation('/modules/' . $strModule . '/php');
                if (isset($_GET['page'])) {
                    echo Initiator::active()->PageBuilder()->createFinalPage($strModuleFileName::buildModuleSubPage($_REQUEST, $_GET['page']), $strModule);
                } else {
                    echo Initiator::active()->PageBuilder()->createFinalPage($strModuleFileName::buildModuleMainPage($_REQUEST), $strModule);
                }
            } else {
                throw new Exception('Module file could not be found: "' . $strModuleFilePath . '"');
            }
        }
    }

    public function moduleExists(string $strModuleName): bool {
        return in_array($strModuleName, $this->arrModuleNames);
    }

    public function loadFile(string $strType, string $strName, string $strModule = null, bool $bolSetHeader = false): string {


        switch ($strType) {
            case 'javascript':
                $strFileExtension = self::JAVASCRIPT_EXT;
                $strDir = self::JAVASCRIPT_DIR;
                $strHeader = self::JAVASCRIPT_HEADER;
                break;
            case 'css':
                $strFileExtension = self::CSS_EXT;
                $strDir = self::CSS_DIR;
                $strHeader = self::CSS_HEADER;
                break;
            case 'template':
                $strFileExtension = self::TEMPLATE_EXT;
                $strDir = self::TEMPLATE_DIR;
                break;
        }

        $strFilePath = $this->getWorkingDir();

        $strFilePath .= $strModule != null ? '/modules/' . $strModule : '';

        $strFilePath .= '/' . $strDir . '/' . $strName . '.' . $strFileExtension;

        if ($bolSetHeader) {
            $intLastMod = filemtime($strFilePath);
            header('Content-type: ' . $strHeader);
            header('Etag: ' . $intLastMod);
            header('Cache-Control: private,max-age=0');
            header_remove('Pragma');
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $intLastMod) {
                header('HTTP/1.1 304 Not Modified', true, 304);
                $strReturn = '';
            }
        }
        if (!isset($strReturn)) {
            $strReturn = file_get_contents($strFilePath);
            if ($strReturn === false) {
                throw new Exception('File Not Found');
            }
        }

        return $strReturn;
    }

    public function printFile(string $strType, string $strName, string $strModule = null): void {
        $strHttpHeader;
        try {
            $strFileContent = $this->loadFile($strType, $strName, $strModule, true);
        } catch (Exception $e) {
            header("HTTP/1.0 404 Not Found");
            die('Error 404');
        }
        echo $strFileContent;
    }

}
