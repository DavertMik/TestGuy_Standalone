#!/usr/bin/env php
<?php
Phar::mapPhar();

require_once 'phar://testguy.phar/vendor/UniversalClassLoader.php';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\Component' => 'phar://testguy.phar/vendor/',
));

$loader->registerPrefixes(array(
    'TestGuy_Command' =>    'phar://testguy.phar/src/standalone/',
    'TestGuy_Module' =>     'phar://testguy.phar/src/standalone/',
    'TestGuy_Standalone' => 'phar://testguy.phar/src/standalone/',
    'TestGuy' =>            'phar://testguy.phar/src/core/',
));

$loader->register();

require_once 'phar://testguy.phar/src/core/BaseTestGuy.php';

@include_once 'mink/autoload.php';
@include_once 'PHPUnit/Autoload.php';

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption;

 $app = new Application('TestGuy', TestGuy_Manager::VERSION);
 $app->add(new TestGuy_Command_Build('build'));
 $app->add(new TestGuy_Command_Run('run'));
 $app->add(new TestGuy_Command_Init('init'));
 $app->add(new TestGuy_Command_Install('install'));
 $app->add(new TestGuy_Command_GenerateScenarios('generate-scenarios'));
 $app->run();

__HALT_COMPILER();