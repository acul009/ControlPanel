<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core;

abstract class PermissionEntity {

    private string $strIdentifier;
    private array $arrPermissions = [];
    private bool $bolAdmin = false;

    function __construct(string $strIdentifier) {
        $this->strIdentifier = $strIdentifier;
    }

    public function getIdentifier(): string {
        return $this->strIdentifier;
    }

    public function mayUseModule(string $strModuleName) {
        return $this->bolAdmin || $this->hasPersmission($strModuleName);
    }

    public function addPermission(string $strModuleName): void {
        if (Initiator::active()->Library()->moduleExists($strModuleName)) {
            $this->arrPermissions[$strModuleName] = true;
        }
    }

    public function hasPersmission(string $strModuleName): bool {
        return isset($this->arrPermissions[$strModuleName]) && $this->arrPermissions[$strModuleName];
    }

    public function removePermission(string $strModuleName): void {
        unset($this->arrPermissions[$strModuleName]);
    }

    public function isAdmin(): bool {
        return $this->bolAdmin;
    }

    public function setAdmin(bool $bolAdmin): void {
        $this->bolAdmin = $bolAdmin;
    }

    protected function cloneMyPermissionsTo(PermissionEntity $objEntity): void {
        $objEntity->arrPermissions = $this->arrPermissions;
        $objEntity->bolAdmin = $this->bolAdmin;
    }

}
