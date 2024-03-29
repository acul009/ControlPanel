<?php

declare(strict_types=1);
$lifetime = 600;
session_start();
setcookie(session_name(), session_id(), time() + $lifetime);

session_destroy();
echo 'Session data deleted!';
