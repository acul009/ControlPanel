<?php

include_once '../php/Initiator.php';
$objInit = new Initiator();


$strAction = isset($_GET['action']) ? $_GET['action'] : '';
$strModule = isset($_GET['module']) ? urldecode($_GET['module']) : '';






switch ($strAction) {

  case 'redeem':
    if (isset($_GET['token'])) {
      $objInit->Authentication()->tryActivateToken($_GET['token']);
    }
    break;

  case 'register':
    if (isset($_POST['user']) && isset($_POST['hash'])) {
      $strReceivedUser = $_POST['user'];
      $strReceivedPassword = $_POST['hash'];
      $objInit->Authentication()->tryRegisterUserFromActiveToken($strReceivedUser, $strReceivedPassword);
    }
    break;

  case 'login':
    if (isset($_POST['user']) && isset($_POST['hash'])) {
      $strReceivedUser = $_POST['user'];
      $strReceivedPassword = $_POST['hash'];
      $objInit->Authentication()->tryLogin($strReceivedUser, $strReceivedPassword);
    }
    break;

  case 'logout':
    $objInit->Authentication()->logout();
    header("Location: ?");
    break;
}




if ($objInit->Authentication()->hasActiveToken()) {
  exit(file_get_contents('../html/register.html'));
}




if ($objInit->Authentication()->isLoggedIn()) {
  $objInit->preparePageBuilder();
  switch ($strAction) {

    case 'open':
      if (isset($_GET['file']) && isset($_GET['type'])) {
        if (isset($_GET['module'])) {
          $objInit->Library()->printFile($_GET['type'], $_GET['file'], $_GET['module']);
        } else {
          echo $objInit->Library()->printFile($_GET['type'], $_GET['file']);
        }
      } else {
        header("HTTP/1.0 418 I'm a teapot");
      }
      exit;
      break;
  }

  if ($strModule != '') {
    $objInit->Library()->runModule($strModule);
    exit;
  } else {
    echo $objInit->PageBuilder()->getMainPage();
  }
} else {
  echo file_get_contents('../html/login.html');
}