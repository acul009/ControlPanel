<?php

declare(strict_types=1);

namespace core;

class Request {
    /*
     * Which HTTP Method was used for the request
     */

    private string $strMethod;

    function __construct() {
        /*
         * Setting Request Type
         */
        $this->strMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     *
     * @return bool Returns Either Request::METHOD_GET or Request::METHOD_POST
     */
    public function getRequestMethod(): bool {
        return $this->bolMethod;
    }

}
