<?php

declare(strict_types=1);

namespace core;

interface ControlPanelModuleInterface {

    public static function buildModuleMainPage(array $arrRequestParameters): string;

    public static function buildModuleSubPage(array $arrRequestParameters, string $strSubPage): string;

    public static function listSubPages(): array;
}
