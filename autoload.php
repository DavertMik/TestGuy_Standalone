<?php

require_once 'vendor/UniversalClassLoader.php';

require_once 'mink/autoload.php';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\Component' => __DIR__ . '/vendor',
));

$loader->registerPrefixes(array(
    'TestGuy_Command' => __DIR__ . '/src/standalone/',
    'TestGuy_Module' => __DIR__ . '/src/standalone/',
    'TestGuy_Standalone' => __DIR__ . '/src/standalone/',
    'TestGuy' => __DIR__ . '/src/core/',
));

$loader->register();
$loader->registerNamespaceFallbacks(array(__DIR__.'/vendor/Mink/vendor'));

require_once 'PHPUnit/Autoload.php';
require_once __DIR__.'/src/core/BaseTestGuy.php';