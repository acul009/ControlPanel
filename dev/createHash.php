<?php

declare(strict_types=1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo '<textarea>' . password_hash($_POST['hash'], PASSWORD_ARGON2I, [
    'memory_cost' => 4194304,
    'time_cost' => 1,
    'threads' => 4
]) . '</textarea>';
