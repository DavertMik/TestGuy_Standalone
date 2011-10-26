<?php
require_once 'phar://vendor/UniversalClassLoader.php';

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony\Component' => 'phar://vendor',
    'Behat\Mink'        => 'phar://vendor/Mink/src',
    'Behat\SahiClient'  => 'phar://vendor/Mink/vendor/SahiClient/src',
    'Buzz'              => 'phar://vendor/Mink/vendor/Buzz/lib',
    'Goutte'            => 'phar://vendor/Mink/vendor/Goutte/src',
    'Zend'              => 'phar://vendor/Mink/vendor/Goutte/vendor/zend/library',
));

$loader->registerPrefixes(array(
    'TestGuy_Command' => 'phar://src/standalone/',
    'TestGuy_Module' => 'phar://src/standalone/',
    'TestGuy_Standalone' => 'phar://src/standalone/',
    'TestGuy' => 'phar://src/core/',


));

$loader->register();
$loader->registerNamespaceFallbacks(array('phar://vendor/Mink/vendor'));

require_once 'PHPUnit/Autoload.php';
require_once 'phar://src/core/BaseTestGuy.php';

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption;

 $app = new Application('TestGuy', TestGuy_Manager::VERSION);
 $app->add(new TestGuy_Command_Build('build'));
 $app->add(new TestGuy_Command_Run('run'));
 $app->add(new TestGuy_Command_GenerateScenarios('generate-scenarios'));
 $app->run();

__HALT_COMPILER();