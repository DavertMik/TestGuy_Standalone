<?php
/**
 * 
 * Author: davert
 * Date: 17.08.11
 * 
 */

require_once 'autoload.php';

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption;

 $app = new Application('TestGuy', TestGuy_Manager::VERSION);
 $app->add(new TestGuy_Command_Build('build'));
 $app->add(new TestGuy_Command_Run('run'));
 $app->run();