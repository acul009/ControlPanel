<?php

declare(strict_types=1);
require_once '../php/loadCore.php';

echo '<pre>';
$libraryManager = \core\LibraryManager::create();
$test = new core\storage\SaveableTest();
$test->setTestString(base64_encode(random_bytes(4)));
$id = $test->save();
print_r($test);
$test = core\storage\SaveableTest::loadFromId(6);
print_r($test);
echo '<br>ID: ' . $id;
