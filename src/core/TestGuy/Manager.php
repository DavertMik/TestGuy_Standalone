<?php
/**
 * Author: davert
 * Date: 10.06.11
 *
 * Class TestGuyManager
 * Description:
 * 
 */
 
class TestGuy_Manager {

	const VERSION = 0.7;

    public static $modules = array();
    public static $methods = array();
    public static $modulesInitialized = false;
	public static $output;

	protected $suite = null;
	protected $tests = array();
	protected $debug = false;
    protected $testcaseClass = 'TestGuy_TestCase';

    public function __construct(PHPUnit_Framework_TestSuite $suite, $debug = false) {
        $this->suite = new $suite;
	    $this->debug = $debug;
    }

    public function addTest($name, $testPath)
    {
        $this->tests[$name] = $testPath;
        $this->suite->addTest(new TestGuy_TestCase('testGuy', array('name' => $name,'file' => $testPath, 'debug' => $this->debug)));
    }

	public function saveTestAsFeature($test, $path) {
		$text = readfile($this->tests[$test]);
	}

	public static function versionString() {
	    return 'TestGuy '.self::VERSION.' running with modules: '.str_replace('TestGuy_Module_','',implode(', ',array_keys(self::$modules))).".\n";
	}

    /**
     * @return PHPUnit_TestSuite
     */
    public function getCurrentSuite() {
        return $this->suite;
    }

    /**
     * @static
     * @param $modulename
     * @param null $path
     * @return TestGuy_Module
     */
    public static function addModule($modulename, $path = null) {
        if ($path) require_once $path;
        $module = new $modulename;
        self::$modules[$modulename] = $module;
        return $module;
    }

    public static function removeModule($dumpModule) {
        foreach (self::$modules as $name => $module) {
            if (get_class($dumpModule) == $name) {
                unset(self::$modules[$name]);
                return;
            }
        }
    }

    public static function initializeModules() {
        foreach (self::$modules as $modulename => $module) {
            $class = new ReflectionClass($modulename);
            $methods = $class->getMethods();
            foreach ($methods as $method) {
			    if (strpos($method->name,'_')===0) continue;
			    if (!$method->isPublic()) continue;
                TestGuy_Manager::$methods[$method->name] = $modulename;
	            TestGuy::$methods[] = $method->name;

            }
        }
        self::$modulesInitialized = true;
    }

    public static function detachModules()
    {
        self::$modulesInitialized = false;
        self::$modules = array();
    }



}
