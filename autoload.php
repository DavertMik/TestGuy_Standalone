<?php

require_once 'vendor/UniversalClassLoader.php';

$loader = new UniversalClassLoader();
$loader->registerNamespace('TestGuyCommand',  __DIR__ . '/src/cli/');
$loader->registerPrefixes(array(
                               'TestGuy_Module'    => __DIR__ . '/src/modules/',
                               'TestGuy'           => __DIR__ . '/src/core/',
));