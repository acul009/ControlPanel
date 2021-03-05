<?php

function lib(): core\LibraryManager {
  return \core\LibraryManager::active();
}

function auth(): core\AuthenticationManager {
  return lib()->getAuthenticationManager();
}
