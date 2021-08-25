<?php

declare(strict_types=1);

function lib(): core\LibraryManager {
    return \core\LibraryManager::active();
}

function auth(): core\AuthenticationManager {
    return lib()->getAuthenticationManager();
}
