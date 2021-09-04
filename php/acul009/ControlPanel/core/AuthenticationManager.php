<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core;

use Initiator;

class AuthenticationManager {

    private const ACCOUNT_FILE = 'users.txt';
    private const TOKEN_FILE = 'tokens.txt';
    private const SESSION_TIME_IN_SECONDS = 30000;

    private array $arrUsers;
    private array $arrTokens;
    private User $objActiveUser;

    public function __construct() {
        $this->prepare();
    }

    public function __destruct() {
        if (isset($this->objActiveUser)) {
            $_SESSION['user'] = $this->objActiveUser->getIdentifier();
        }
    }

    public function getActiveUser(): User {
        return $this->objActiveUser;
    }

    public function getAvailablePermissions(): array {
        return\Initiator::active()->Library()->listModules();
    }

    public function getAllowedModules() {
        $arrAvailableModuleList = \Initiator::active()->Library()->listModules();
        $arrAllowedModuleList = [];
        foreach ($arrAvailableModuleList as $strModule) {
            if (Initiator::active()->Authentication()->activeUser()->mayUseModule($strModule)) {
                $arrAllowedModuleList[] = $strModule;
            }
        }
        return $arrAllowedModuleList;
    }

    public function tryLogin(string $strUser, string $strPassword): void {

        //echo '<br> trying login for user: ' . $strUser;
        //echo '<br>args set';
        //echo '<br>User Exists: ' . ($this->userExists($strUser) ? 'true' : 'false');
        if ($this->userExists($strUser)) {
            //echo'<br>User found: ' . $strUser;
            $objUser = $this->getUserByName($strUser);
            if ($objUser->verifyPassword($strPassword)) {
                $this->setActiveUser($objUser);
                //echo '<br>Current User:';
                return;
            } else {
                //echo '<br>Password incorrect';
            }
        }
        $this->logout();
    }

    public function logout() {
        //echo '<br>loggin out';
        $this->loadSession();
        session_unset();
        session_destroy();
    }

    public function isLoggedIn(): bool {
        if ($this->sessionStillActive() && isset($this->objActiveUser)) {
            $this->updateLastActive();
            return true;
        }
        return false;
    }

    private function sessionStillActive(): bool {
        if (isset($_SESSION['lastActive'])) {
            $intCurrentTime = time();
            $intLastActive = intval($_SESSION['lastActive']);
            $intLastActiveAgo = $intCurrentTime - $intLastActive;
            return $intLastActiveAgo < self::SESSION_TIME_IN_SECONDS;
        }
        return false;
    }

    private function setActiveUser(User $objUser): void {
        $this->objActiveUser = $objUser;
        session_regenerate_id(true);
        $this->updateLastActive();
    }

    private function updateLastActive(): void {
        $_SESSION['lastActive'] = time();
    }

    private function loadUsers(): void {
//    $this->arrUsers = [];
//    $arrCSVUsers = explode(self::USER_DELIMITER, file_get_contents(Initiator::active()->Library()->getWorkingDir() . '/config/' . static::ACCOUNT_FILE));
//    foreach ($arrCSVUsers as $strUserData) {
//      $objUser = unserialize($strUserData);
//      $this->arrUsers[$objUser->getUserName()] = $objUser;
//    }

        $strUserFilePath = \Initiator::active()->Library()->getWorkingDir() . '/config/' . static::ACCOUNT_FILE;
        if (!file_exists($strUserFilePath)) {
            session_unset();
            $this->arrUsers = [];
            $this->saveUsers();
            $objAdminToken = Token::createFirstAdminToken();
            $this->arrTokens[$objAdminToken->getIdentifier()] = $objAdminToken;
            $this->tryActivateToken($objAdminToken->getIdentifier());
        } else {
            $strUserData = file_get_contents($strUserFilePath);
            $this->arrUsers = unserialize($strUserData);
        }
    }

    private function loadTokens(): void {
        $strTokenFilePath = \Initiator::active()->Library()->getWorkingDir() . '/config/' . static::TOKEN_FILE;
        if (!file_exists($strTokenFilePath)) {
            $this->arrTokens = [];
            $this->saveTokens();
        } else {
            $strTokenData = file_get_contents($strTokenFilePath);
            $this->arrTokens = unserialize($strTokenData);
        }
    }

    public function saveUsers(): void {
        if (isset($this->arrUsers)) {
            file_put_contents(\Initiator::active()->Library()->getWorkingDir() . '/config/' . static::ACCOUNT_FILE, serialize($this->arrUsers));
        }
    }

    public function saveTokens(): void {
        if (isset($this->arrTokens)) {
            file_put_contents(\Initiator::active()->Library()->getWorkingDir() . '/config/' . static::TOKEN_FILE, serialize($this->arrTokens));
        }
    }

    public function getUserByName(string $strUserName): User {
        return $this->arrUsers[$strUserName];
    }

    private function userExists(string $strUserName): bool {
        return isset($this->arrUsers[$strUserName]);
    }

    private function loadSession() {
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    private function prepare(): void {
        if (!isset($this->arrUsers)) {
            $this->loadUsers();
        }
        if (!isset($this->arrTokens)) {
            $this->loadTokens();
        }
    }

    public function listUsers(): array {
        return $this->arrUsers;
    }

    public function genToken(): Token {
        $objToken = new Token();
        $this->arrTokens[$objToken->getIdentifier()] = $objToken;
        return $objToken;
    }

    public function getToken($strIdentifier): Token {
        return $this->arrTokens[$strIdentifier];
    }

    public function listTokens(): array {
        return $this->arrTokens;
    }

    public function tokenExists(string $strIdentifier): bool {
        return key_exists($strIdentifier, $this->arrTokens);
    }

    public function deleteUser(string $strUserName): void {
        unset($this->arrUsers[$strUserName]);
    }

    public function deleteToken(string $strIdentifier): void {
        unset($this->arrTokens[$strIdentifier]);
    }

    public function tryActivateToken(string $strIdentifier): void {
        if ($this->tokenExists($strIdentifier)) {
            $this->updateLastActive();
            $objToken = $this->getToken($strIdentifier);
            $_SESSION['token'] = serialize($objToken);
            $this->deleteToken($strIdentifier);
            $this->saveTokens();
        }
    }

    public function hasActiveToken(): bool {
        return isset($_SESSION['token']);
    }

    public function getActiveToken(): Token {
        return unserialize($_SESSION['token']);
    }

    public function tryRegisterUserFromActiveToken(string $strUserName, string $strPassword): void {

        if ($this->hasActiveToken()) {
            if ($this->sessionStillActive()) {
                /** @var Token $objToken */
                $objToken = $this->getActiveToken();
                if (!$this->userExists($strUserName)) {
                    $objUser = $objToken->promoteToUser($strUserName, $strPassword);
                    $this->arrUsers[$strUserName] = $objUser;
                    $this->saveUsers();
                    $this->removeActiveToken();
                    $this->tryLogin($strUserName, $strPassword);
                }
            } else {
                $this->removeActiveToken();
            }
        }
    }

    public function removeActiveToken(): void {
        unset($_SESSION['token']);
    }

}
