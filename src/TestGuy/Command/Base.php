<?php
/**
 * Author: davert
 * Date: 17.08.11
 *
 * Class TestGuy_Command_Base
 * Description:
 * 
 */

use \Symfony\Component\Yaml\Yaml;

class TestGuy_Command_Base extends \Symfony\Component\Console\Command\Command {

    protected $config;
    protected $suites;
    protected $tests_path;

    protected function configure()
    {
        $this->config = Yaml::parse('testguy.yml');
        $this->tests_path = $this->config['path']['tests'];
        $this->suites = Yaml::parse($this->tests_path . '/suites.yml');

    }
    
}
