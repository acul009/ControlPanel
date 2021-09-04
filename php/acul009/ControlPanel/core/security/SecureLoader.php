<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\security;

use core\permissions\PermissionManager;
use core\security\ProtectedSingleton;

/**
 * This class provides a way to load php files based on permissions.
 * It's supposed to allow the installation of plugins without worrying about security.
 *
 * @author acul
 */
class SecureLoader extends ProtectedSingleton {

    private const SCAN_CACHE_ID = 1;
    private const FUNCTION_PERMISSIONS = [
        'include' => '/php/include',
        'include_once' => '/php/include',
        'eval' => '/php/eval'
    ];

    private SecurityScanCache $cache;
    private PermissionManager $pMan;
    private string $modulesDir;

    protected function init(string $modulesDir, PermissionManager $pMan): void {
        $this->cache = SecurityScanCache::loadFromId(self::SCAN_CACHE_ID);
        $this->modulesDir = $modulesDir;
        $this->$pMan = $pMan;
    }

    public function load(string $className) {
        $moduleClassArray = explode('\\', $className, 2);
        $filePath = $this->modulesDir
                . DIRECTORY_SEPARATOR . $moduleClassArray[0]
                . DIRECTORY_SEPARATOR . PHP_SUBDIR
                . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $moduleClassArray[1]);
        if ($this->mayLoadFile($filePath)) {
            include $filePath;
        }
    }

    private function mayLoadFile(string $filePath): bool {
        $neededPermissions = $this->scanFileForNeededPermissions($filePath);
        foreach ($neededPermissions as $permission) {
            if (!$this->pMan->may($permission)) {
                return false;
            }
        }
        return true;
    }

    private function scanFileForNeededPermissions(string $filePath): array {
        if ($this->cache->isScanStillValid($filePath)) {
            return $this->cache->getNeededPermissionsForFile($filePath);
        }
        $fileContent = file_get_contents($filePath);
        $permissions = [];
        $regex = '/[ \n](' . implode('|', array_keys(self::FUNCTION_PERMISSIONS)) . ')[ (]/';
        $functions = [];
        preg_match_all($regex, $fileContent, $functions);
        $neededPermissions = array_intersect_key(array_flip($functions), self::FUNCTION_PERMISSIONS);
        $this->cache->setNeededPermissionsForFile($filePath, $neededPermissions);
        return $neededPermissions;
    }

    function __destruct() {
        $this->cache->save(self::SCAN_CACHE_ID);
    }

}
