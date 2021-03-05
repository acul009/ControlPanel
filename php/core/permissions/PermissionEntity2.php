<?php

namespace core\permissions;

/**
 * This Entity represents An Object, which can own permissions.
 * It is not supposed to be a a parent class for this object.
 * All Permission manipulation is supposed to be done via the PermissionManager.
 *
 * A Permission is a case insensitive string which basically lokks like a path.
 * e.g.:    /core/permissions
 *          /core/permissions/add
 *          /adminpanel/editusers
 *
 * If an entity has a permission, all sub-permissions will also be viewed as owned
 * e.g.: /core also includes /core/permissions
 *
 * this also means that the root-Permission (/) includes all others
 *
 * A caveat of this specific implementation is, that a permission comepletly overrides
 * it's children which will be gone after the permission is removed again
 *
 * e.g.:    add permission /core/users
 *          add permission /core
 *          remove permission /core
 *          -> permission /core/users won't be set
 *
 * @author acul
 */
class PermissionEntity2 {

  public const SEPERATOR = '/';
  private const PERMISSION_REGEX = '!^/[a-zA-Z]*(?:/[a-zA-Z]+)*$!';

  private $arrPermissionTree = [];

  public function addPermission(string $strPermission): void {
    $arrSubKeys = $this->getKeyArrayFromPermission($strPermission);
    $arrPermissionTreePointer = &$this->arrPermissionTree;
    foreach ($arrSubKeys as $strPermissionSubKey) {
      if ($arrPermissionTreePointer === true) {
        return;
      }

      if (!isset($arrPermissionTreePointer[$strPermissionSubKey])) {
        $arrPermissionTreePointer[$strPermissionSubKey] = [];
      }
      $arrPermissionTreePointer = &$arrPermissionTreePointer[$strPermissionSubKey];
    }
    $arrPermissionTreePointer = true;
  }

  public function removePermission(string $strPermission): bool {
    $arrSubKeys = $this->getKeyArrayFromPermission($strPermission);
    $strLastSubKey = array_pop($arrSubKeys);
    $arrRemovalList = [];
    $arrPermissionTreePointer = &$this->arrPermissionTree;
    foreach ($arrSubKeys as $strPermissionSubKey) {
      if ($arrPermissionTreePointer === true) {
        return false;
      }

      if (!isset($arrPermissionTreePointer[$strPermissionSubKey])) {
        return true;
      }

      if (count($arrPermissionTreePointer[$strPermissionSubKey]) <= 1) {
        $arrRemovalList[$strPermissionSubKey] = &$arrPermissionTreePointer;
      } else {
        $arrRemovalList = [];
      }
      $arrPermissionTreePointer = &$arrPermissionTreePointer[$strPermissionSubKey];
    }
    unset($arrPermissionTreePointer[$strLastSubKey]);
    foreach ($arrRemovalList as $strSubKey => &$arrTreePart) {
      unset($arrTreePart[$strSubKey]);
    }
    return true;
  }

  public function hasFullPermission(string $strPermission): bool {
    return $this->hasPermission($strPermission, false);
  }

  public function hasSubPermission(string $strPermission): bool {
    return $this->hasPermission($strPermission, true);
  }

  private function hasPermission(string $strPermission, bool $bolSubPermission = false): bool {
    $arrSubKeys = $this->getKeyArrayFromPermission($strPermission);
    $arrPermissionTreePointer = &$this->arrPermissionTree;
    foreach ($arrSubKeys as $strPermissionSubKey) {
      if ($arrPermissionTreePointer === true) {
        return true;
      }

      if (!isset($arrPermissionTreePointer[$strPermissionSubKey])) {
        return false;
      }
      $arrPermissionTreePointer = &$arrPermissionTreePointer[$strPermissionSubKey];
    }
    return $bolSubPermission || $arrPermissionTreePointer === true;
  }

  private function getKeyArrayFromPermission(string $strPermission) {
    $this->throwExceptionIfPermissionInvalid($strPermission);
    if (!isset($strPermission[1])) {
      return [];
    }
    $arrCaseSensitiveParts = array_slice(explode(self::SEPERATOR, $strPermission), 1);
    $arrToLowerParts = array_map('strtolower', $arrCaseSensitiveParts);
    return $arrToLowerParts;
  }

  private function throwExceptionIfPermissionInvalid(string $strPermission): void {
    if (!self::isPermissionValid($strPermission)) {
      throw new InvalidPermissionException($strPermission);
    }
  }

  public static function isPermissionValid(string $strPermission): bool {
    return (bool) preg_match(self::PERMISSION_REGEX, $strPermission);
  }

}
