<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core;

abstract class Endpoint extends Plugin {

    protected Request $request;

    function __construct(Request $request) {
        $this->request = $request;
    }

    public abstract function handleRequest(Request $objRequest): string;

    public static function getRelativePluginPath(): string {
        return '/endpoints';
    }

    public static function getLink(): string {
        
    }

}
