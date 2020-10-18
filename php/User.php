<?php

/**
 * Description of User
 *
 * @author acul
 */
class User extends PermissionEntity {

  private string $strPasswordHash;

  function __construct(string $strUserName, string $strPassword) {
    parent::__construct($strUserName);
    $this->strPasswordHash = password_hash($strPassword, PASSWORD_ARGON2ID, [
        'memory_cost' => 4194304,
        'time_cost' => 1,
        'threads' => 4
    ]);
  }

  public function verifyPassword(string $strPassword): bool {
    return password_verify($strPassword, $this->strPasswordHash);
  }

}
