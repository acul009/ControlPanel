<?php

declare(strict_types=1);
include_once '../php/core/LibraryManager.php';

use \acul009\ControlPanel\core\AuthenticationManager;
use \acul009\ControlPanel\core\LibraryManager;
use gui\PageBuilder;

class Initiator {

    private LibraryManager $objLibrary;
    private AuthenticationManager $objAuth;
    private PageBuilder $objPageBuilder;
    private static Initiator $objActive;

    public function __construct() {
        self::$objActive = $this;
        $this->objLibrary = new LibraryManager();
        $this->objAuth = new AuthenticationManager();
    }

    public function Authentication(): AuthenticationManager {
        return $this->objAuth;
    }

    public function Library(): LibraryManager {
        return $this->objLibrary;
    }

    public function preparePageBuilder(): void {
        $this->objPageBuilder = new PageBuilder();
    }

    public function PageBuilder(): PageBuilder {
        return $this->objPageBuilder;
    }

    public static function active(): Initiator {
        return self::$objActive;
    }

}
