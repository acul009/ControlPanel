<?php

declare(strict_types=1);

namespace acul009\ControlPanel\gui;

use Initiator;
use utils\TemplateFiller;

class PageBuilderLegacy {

    private string $strMainTemplate;

    public function __construct() {
        $this->buildMainTemplate();
    }

    public function getMainPage(): string {
        $objIndexTemplate = new TemplateFiller('IndexTemplate');
        return $this->createFinalPage($objIndexTemplate);
    }

    public function buildMainTemplate(): void {
        $strMenuEntries = '';

        $arrAllowedModules = Initiator::active()->Authentication()->getAllowedModules();
        $objListEntryBuilder = new TemplateFiller('MenuEntryTemplate');

        foreach ($arrAllowedModules as $strModule) {
            $objListEntryBuilder->setSubstituteArray(['name' => $strModule, 'link' => ('./index.php?module=' . urlencode($strModule)), 'class' => 'menuEntry']);
            $strMenuEntries .= $objListEntryBuilder;
        }

        $objMainMenuBuilder = new TemplateFiller('MainMenuTemplate');
        $objMainMenuBuilder->setSubstituteArray(['menu' => $strMenuEntries]);

        $objSiteBuilder = new TemplateFiller('MainTemplate');

        $objSiteBuilder->setSubstituteArray(['menu' => $objMainMenuBuilder]);

        $this->strMainTemplate = $objSiteBuilder;
    }

    public function createFinalPage(string $strContent, string $strModule = '') {
        $strSubMenu = '';
        $strMeta = '';

        if ($strModule != '') {

            //building sub-Menu
            $strSubMenuEntries = '';
            $strModuleFileName = str_replace(' ', '_', $strModule);
            $arrSubMenu = $strModuleFileName::listSubPages();
            $objSubMenuEntriesBuilder = new TemplateFiller('MenuEntryTemplate');
            foreach ($arrSubMenu as $strName) {
                $strLink = './index.php?module=' . urlencode($strModule) . '&page=' . urlencode($strName);
                $objSubMenuEntriesBuilder->setSubstituteArray(['name' => $strName, 'link' => $strLink, 'class' => 'subMenuEntry']);
                $strSubMenuEntries .= $objSubMenuEntriesBuilder;
            }

            $objSubMenuBuilder = new TemplateFiller('SubMenuTemplate');
            $objSubMenuBuilder->setSubstituteArray(['menu' => $strSubMenuEntries]);
            $strSubMenu = $objSubMenuBuilder;

            //linking neccesary css
            $objCssTemplate = new TemplateFiller('cssTemplate');
            $objCssTemplate->setSubstituteArray(['file' => $strModuleFileName, 'module' => $strModule]);
            $strMeta .= $objCssTemplate;
            $strMeta .= '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
            $objJsTemplate = new TemplateFiller('jsTemplate');
            $objJsTemplate->setSubstituteArray(['file' => $strModuleFileName, 'module' => $strModule]);
            $strMeta .= $objJsTemplate;
        }

        $strPage = str_replace('{submenu}', $strSubMenu, $this->strMainTemplate);
        $strPage = str_replace('{meta}', $strMeta, $strPage);

        return str_replace('{content}', $strContent, $strPage);
    }

}
