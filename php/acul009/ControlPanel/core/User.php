<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core;

class User extends PermissionEntity {

    private string $strPasswordHash;

    function __construct(string $strUserName, string $strPassword) {
        parent::__construct($strUserName);
        $this->strPasswordHash = password_hash($strPassword, PASSWORD_ARGON2ID);
    }

    public function verifyPassword(string $strPassword): bool {
        return password_verify($strPassword, $this->strPasswordHash);
    }

}
