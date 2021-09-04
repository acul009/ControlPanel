<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\permissions;

use core\LibraryManager;

/**
 * The Permission Manager is the actual interface for interacting with permissions.
 * It manages what is currently allowed based on user and module permissions.
 *
 * @author acul
 */
class PermissionManager {

    private PermissionEntity2 $modulePermissions;
    private PermissionEntity2 $userPermissions;

    function __construct() {
        
    }

    public function may(string $permission): bool {
        
    }

}
