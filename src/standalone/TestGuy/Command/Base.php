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

class TestGuy_Command_Base extends \Symfony\Component\Console\Command\Command
{

	protected $config;
	protected $suites;
	protected $tests_path;

	protected function configure()
	{
		$config = file_exists('testguy.yml') ? Yaml::parse('testguy.yml') : array();
		$distConfig = file_exists('testguy.dist.yml') ? Yaml::parse('testguy.dist.yml') : array();
		$this->config = array_merge($distConfig, $config);

		if (isset($this->config['paths'])) {
            $this->tests_path =  $this->config['paths']['tests'];
            $suites = file_exists($this->tests_path . '/suites.yml') ? Yaml::parse($this->tests_path . '/suites.yml') : array();
            $distSuites = file_exists($this->tests_path . '/suites.dist.yml') ? Yaml::parse($this->tests_path . '/suites.dist.yml') : array();
            $this->suites = array_merge($distSuites, $suites);
        }

	}

}
