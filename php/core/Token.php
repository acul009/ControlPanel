<?php

declare(strict_types=1);

namespace core;

class Token extends PermissionEntity {

    function __construct(string $strIdent = null) {
        if ($strIdent == null) {
            $strIdent = base64_encode(random_bytes(100));
        }
        $strIdent = strtr($strIdent, '+/', '-_');
        $strIdent = rtrim($strIdent, '=');
        parent::__construct($strIdent);
    }

    public function promoteToUser($strUserName, $strPassword): User {
        $objUser = new User($strUserName, $strPassword);
        $this->cloneMyPermissionsTo($objUser);
        return $objUser;
    }

    public static function createFirstAdminToken(): Token {
        $objToken = new Token('admin');
        $objToken->setAdmin(true);
        return $objToken;
    }

}
