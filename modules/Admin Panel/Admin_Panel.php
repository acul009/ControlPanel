<?php

/**
 * Description of AdminPanel
 *
 * @author acul
 */
class Admin_Panel implements ControlPanelModuleInterface {

  private const SUB_PAGES = ['Users', 'Tokens'];

  public static function buildModuleMainPage(array $arrRequestParameters): string {
    return self::buildModuleSubPage($arrRequestParameters, self::SUB_PAGES[0]);
  }

  public static function buildModuleSubPage(array $arrRequestParameters, string $strSubPage): string {
    $strPage = 'Admin Panel';

    switch ($strSubPage) {

      case self::SUB_PAGES[0]:
        $strPage = self::buildUsersPage();
        break;

      case self::SUB_PAGES[1]:
        $strPage = self::buildTokensPage();
        break;

      case 'delete':
        self::deleteEntity($arrRequestParameters);
        exit;

      case 'update':
        self::updateEntity($arrRequestParameters);
        exit;

      case 'new token':
        echo self::newToken();
        exit;
    }

    return $strPage;
  }

  public static function listSubPages(): array {
    return self::SUB_PAGES;
  }

  private static function buildUsersPage(): string {
    $strUserList = '';
    $arrUsers = Initiator::active()->Authentication()->listUsers();


    $objPermissionHtml = new TemplateFiller('PermissionTemplate', 'Admin Panel');
    $objUserHtml = new TemplateFiller('UserTemplate', 'Admin Panel');
    $objUserListHtml = new TemplateFiller('UserListTemplate', 'Admin Panel');

    foreach ($arrUsers as $objUser) {
      $strUserList .= self::buildPermissionEditItem($objUser, $objPermissionHtml, $objUserHtml);
    }

    $objUserListHtml->setSubstituteArray(['list' => $strUserList]);
    return $objUserListHtml->__toString();
  }

  private static function buildTokensPage(): string {
    $strTokenList = '';
    $arrTokens = Initiator::active()->Authentication()->listTokens();


    $objPermissionHtml = new TemplateFiller('PermissionTemplate', 'Admin Panel');
    $objTokenHtml = new TemplateFiller('UserTemplate', 'Admin Panel');
    $objTokenListHtml = new TemplateFiller('TokenListTemplate', 'Admin Panel');

    foreach ($arrTokens as $objToken) {
      $strTokenList .= self::buildPermissionEditItem($objToken, $objPermissionHtml, $objTokenHtml);
    }

    $objTokenListHtml->setSubstituteArray(['list' => $strTokenList]);
    return $objTokenListHtml->__toString();
  }

  private static function buildPermissionEditItem(PermissionEntity $objEntity, TemplateFiller $objPermissionHtml, TemplateFiller $objUserHtml): string {
    $strPermissions = '';

    foreach (Initiator::active()->Library()->listModules() as $strModule) {
      $objPermissionHtml->setSubstituteArray([
          'permission' => $strModule,
          'checked' => $objEntity->hasPersmission($strModule) ? 'checked' : ''
      ]);
      $strPermissions .= $objPermissionHtml;
    }

    $objUserHtml->setSubstituteArray([
        'username' => htmlentities($objEntity->getIdentifier()),
        'admin' => ($objEntity->isAdmin() ? 'selected' : ''),
        'permissions' => $strPermissions,
        'type' => get_class($objEntity),
        'inject' => (get_class($objEntity) == 'Token' ? '<div class="copyLink button sameSize">Copy Link</div>' : '')
    ]);
    return (string) $objUserHtml;
  }

  private static function updateEntity(array $arrRequestParameters): void {

    if (isset($arrRequestParameters['User'])) {
      $objPermissionEntity = Initiator::active()->Authentication()->getUserByName($arrRequestParameters['User']);
    } else if (isset($arrRequestParameters['Token'])) {
      $objPermissionEntity = Initiator::active()->Authentication()->getToken($arrRequestParameters['Token']);
    }

    if (isset($objPermissionEntity)) {
      if (isset($arrRequestParameters['allow'])) {
        $arrAllow = explode('_', $arrRequestParameters['allow']);
        foreach ($arrAllow as $strAllow) {
          $objPermissionEntity->addPermission($strAllow);
        }
      }
      if (isset($arrRequestParameters['deny'])) {
        $arrDeny = explode('_', $arrRequestParameters['deny']);
        foreach ($arrDeny as $strDeny) {
          $objPermissionEntity->removePermission($strDeny);
        }
      }
      if (isset($arrRequestParameters['admin'])) {
        $objPermissionEntity->setAdmin($arrRequestParameters['admin'] == 'true');
      }
      Initiator::active()->Authentication()->saveUsers();
      Initiator::active()->Authentication()->saveTokens();
    }

    exit;
  }

  private static function newToken(): string {
    $objAuthentication = Initiator::active()->Authentication();
    $objToken = $objAuthentication->genToken();
    $objAuthentication->saveTokens();
    $objPermissionHtml = new TemplateFiller('PermissionTemplate', 'Admin Panel');
    $objTokenHtml = new TemplateFiller('UserTemplate', 'Admin Panel');

    return self::buildPermissionEditItem($objToken, $objPermissionHtml, $objTokenHtml);
  }

  private static function deleteEntity(array $arrRequestParameters): void {
    if (isset($arrRequestParameters['User'])) {
      $objPermissionEntity = Initiator::active()->Authentication()->deleteUser($arrRequestParameters['User']);
      Initiator::active()->Authentication()->saveUsers();
    } else if (isset($arrRequestParameters['Token'])) {
      $objPermissionEntity = Initiator::active()->Authentication()->deleteToken($arrRequestParameters['Token']);
      Initiator::active()->Authentication()->saveTokens();
    }
  }

}
