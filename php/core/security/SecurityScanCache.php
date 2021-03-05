<?php

namespace core\security;

use \core\storage\SaveableObject;

/**
 * Description of SecurityCache
 *
 * @author acul
 */
class SecurityScanCache extends SaveableObject {

  protected array $lastFileScans = [];
  protected array $filePermissions = [];

  public function isScanStillValid(string $filePath) {
    if (!isset($this->lastFileScans[$filePath])) {
      return false;
    }
    return $this->lastFileScans[$filePath] <= time();
  }

  private function updateFileScanDate(string $filePath) {
    $this->lastFileScans[$filePath] = time();
  }

  public function dropOutdatedFilepaths() {
    foreach ($lastFileScans as $filePath => $lastScan) {
      if (!file_exists($filePath)) {
        unset($lastFileScans[$filePath]);
        unset($filePermissions[$filePath]);
      }
    }
  }

  public function getNeededPermissionsForFile(string $filePath): array {
    return $this->filePermissions[$filePath];
  }

  public function setNeededPermissionsForFile(string $filePath, array $neededPermissions) {
    $this->updateFileScanDate($filePath);
    $this->filePermissions[$filePath] = $neededPermissions;
  }

}
