<?php

/**
 * Description of User
 *
 * @author acul
 */
class Token extends PermissionEntity {

  function __construct() {
    $strIdent = base64_encode(random_bytes(100));
    $strIdent = strtr($strIdent, '+/', '-_');
    $strIdent = rtrim($strIdent, '=');
    parent::__construct($strIdent);
  }

  public function promoteToUser($strUserName, $strPassword): User {
    $objUser = new User($strUserName, $strPassword);
    $this->cloneMyPermissionsTo($objUser);
    return $objUser;
  }

}
