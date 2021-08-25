<?php

declare(strict_types=1);

namespace core;

/**
 * The Base Class fpr all Plugins.
 * If you want to implement a plugin interface you can extend this class.
 * Add the relative Path to specify where in the Plugin folder the finished Plugins should be located
 * then just declare the abstract Methods you want the Plugins to implement.
 *
 * @author acul
 */
abstract class Plugin {

    private const EXTRACT_NAME_REGEX = '/(.*)\.php$/';

    public abstract function getRequiredPermissions(): array;

    public abstract function getRequiredSubPermissions(): array;

    /**
     * This function should be implemented in the abstract Plugin definition
     */
    public static abstract function getRelativePluginPath(): string;

    public static function getAvailablePluginList(): array {
        $strRelativePath = static::getRelativePluginPath();
        /*
         * TODO:
         * unfortunatly this has to include the Plugin files to Check for neccesary Permissions
         */
    }

    public static function initiatePlugin($strPluginName): void {
        /*
         * TODO
         * Note: redirect include through LibraryManager
         */
    }

}
