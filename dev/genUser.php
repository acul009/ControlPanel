<?php

declare(strict_types=1);
include_once '../php/PermissionEntity.php';
include_once '../php/User.php';

$objUser = new User('acul', 'edac4490ebaee6bc2bbcbbb49acd9a8c8608b8144cbb8de3e74ebbfa441503b5c50042aea0107be7f0cc5dd4ab9217d1694c6dc2728cba8e94427322826bb301');
$objUser->setAdmin(true);
$strSerial = serialize(['acul' => $objUser]);
$objLoadedUser = unserialize($strSerial);
var_dump($objLoadedUser);
echo '<br>';
echo htmlentities($strSerial);
$fp = fopen('../config/users.txt', "w");
fwrite($fp, $strSerial);
fclose($fp);
