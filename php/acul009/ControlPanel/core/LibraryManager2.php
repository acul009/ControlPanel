<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core;

use \acul009\ControlPanel\core\security\SecureLoader;
use \acul009\ControlPanel\core\Storage\Saveable\Base;
use \acul009\ControlPanel\core\drivers\SaveableDriver;
use \acul009\ControlPanel\core\security\ProtectedSingleton;

/**
 * TODO:
 * This draft was based on JSON-manifests for Modules.
 * It should be reqritten for Plugins.
 */
class LibraryManager2 extends ProtectedSingleton {

    private const FILEPATH_FORBIDDEN_SEQUENCES = ['./', '/..'];
    private const MODULE_CONFIG = 'ModuleConfig.json';
    private const MODULE_PATH_KEY = 'path';
    private const MODULE_NAME_KEY = 'name';
    private const MODULE_CLASS_KEY = 'class';
    private const PHP_DIR = PHP_SUBDIR;
    private const DATA_DIR = 'data';
    private const MODULE_DIR = 'modules';
    private const GLOBAL_FUNCTIONS_FILE = 'functions.php';

    private SecureLoader $secLoader;
    private ApiProvider $api;
    private string $workingDir;

    protected function init(): void {
        $this->updateWorkingDir();
        Base::initCache();
        $this->api = ApiProvider::create($this);
        SaveableDriver::initDriver($this->api);
    }

    private function updateWorkingDir(): void {
        $this->workingDir = dirname(getcwd(), 1);
    }

    private function getWorkingDir(): string {
        return $this->workingDir;
    }

    public function getDataDir(): string {
        return $this->getWorkingDir() . DIRECTORY_SEPARATOR . self::DATA_DIR;
    }

    public function getModuleDir(): string {
        return $this->getWorkingDir() . DIRECTORY_SEPARATOR . self::MODULE_DIR;
    }

    private function registerCustomLoader() {
        $this->secLoader = SecureLoader::create($this->getModuleDir(), $pMan);
        spl_autoload_register([$this->secLoader, 'load']);
    }

}
