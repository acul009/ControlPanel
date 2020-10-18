<?php

$lifetime = 600;
session_start();
setcookie(session_name(), session_id(), time() + $lifetime);

$loggedIn = isset($_SESSION) && isset($_SESSION['login']) && boolval($_SESSION['login']);
$loggedIn = $loggedIn ? 'true' : 'false';
echo '<br>logged in: ' . $loggedIn;
