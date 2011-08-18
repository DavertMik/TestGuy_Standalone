<?php

require_once 'vendor/UniversalClassLoader.php';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\Component' => __DIR__ . '/vendor',
));
$loader->registerPrefixes(array(
    'TestGuy_Command' => __DIR__ . '/src/',
    'TestGuy_Module' => __DIR__ . '/src/',
    'TestGuy_Standalone' => __DIR__ . '/src/',
    'TestGuy' => __DIR__ . '/src/core/',
));

$loader->register();

require_once 'PHPUnit/Autoload.php';
require_once __DIR__.'/src/core/BaseTestGuy.php';