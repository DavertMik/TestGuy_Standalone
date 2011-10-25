<?php
/**
 * Author: davert
 * Date: 17.08.11
 *
 * Class Manager
 * Description:
 * 
 */
 
class TestGuy_Standalone_Manager extends TestGuy_Manager {

    protected $bootstrap = null;

    public static function init($settings = array())
    {
        if (!isset($settings['modules'])) throw new Exception('No modules configured!');
        TestGuy_Manager::detachModules();
        $modules = $settings['modules'];
        foreach ($modules as $module_name) {
            $module = TestGuy_Manager::addModule('TestGuy_Module_'.$module_name);
            if (!isset($settings[$module_name])) {
                $settings[$module_name] = array();
            }
            $module->_setConfig($settings[$module_name]);
        }
        TestGuy_Manager::initializeModules();
    }

	public function addTest($name, $testPath = null)
	{
	    $this->tests[$name] = $testPath;
	    $this->suite->addTest(new TestGuy_TestCase('testGuy', array(
			'name' => $name,'file' => $testPath,
            'debug' => $this->debug,
	        'bootstrap' => $this->bootstrap)));
	}

    public function setBootstrtap($bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    public function loadTest($name, $path) {
        $testFiles = \Symfony\Component\Finder\Finder::create()->files()->name($name.'Spec.php')->in($path);
        foreach ($testFiles as $test) {
            $this->addTest(basename($test), $test);
            break;
        }
    }

    public function loadTests($path)
    {
        $testFiles = \Symfony\Component\Finder\Finder::create()->files()->name('*Spec.php')->in($path);
        foreach ($testFiles as $test) {
            $this->addTest(basename($test), $test);
        }
    }

}
