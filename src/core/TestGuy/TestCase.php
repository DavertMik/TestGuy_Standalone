<?php
/**
 * Author: davert
 * Date: 08.06.11
 *
 * Class TestGuy_TestCase
 * Description:
 * 
 */
 
class TestGuy_TestCase extends PHPUnit_Framework_TestCase implements PHPUnit_Framework_SelfDescribing {

    protected $testfile = null;
	protected $output;
	protected $debug;
	protected $features = array();
	protected $scenario;
	protected $bootstrap = null;
	protected $stopped = false;
	protected $trace = array();


    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        if (!isset($data['file'])) throw new Exception('File with test scenario not set. Use array(file => filepath) to set a scenario');
        $this->specName = str_replace('Spec.php','',basename($data['file']));
        $this->scenario = new TestGuy_Scenario($this);
        $this->testfile = $data['file'];
	    $this->bootstrap = isset($data['bootstrap']) ? $data['bootstrap'] : null;
	    $this->output = new TestGuy_Output();
	    $this->debug = isset($data['debug']) ? $data['debug'] : false;
    }

	
	public function getName($withDataSet = TRUE) {
	    return $this->specName;
	}

	public function getFileName() {
	    return $this->getName().'Spec.php';
	}

	/**
	 * @return TestGuy_Scenario
	 */
	public function getScenario() {
	    return $this->scenario;
	}

	public function getScenarioText() {
		$text = implode("\r\n", $this->scenario->getSteps());
		$text = str_replace(array('((','))'), array('...', ''), $text);
		return $text = strtoupper('I want to '.$this->scenario->getFeature())."\n\n".$text;
	}

    public function __call($command, $args) {
//	    echo $command;
        if (strrpos('Test', $command) !== 0) return;
        $this->testGuy();
    }

    /**
        * @test
        */
    public function testGuy() {
        // require ''
		$this->loadScenario();
	    $this->output->writeln("Trying to [[{$this->scenario->getFeature()}]] (". basename($this->testfile).") ");
	    if ($this->debug) $this->output->writeln("Scenario:\n");

	    foreach (TestGuy_Manager::$modules as $module) {
	        $module->_before($this);
	    }

	    try {
		    $this->scenario->run();
	    } catch (PHPUnit_Framework_ExpectationFailedException $fail) {
		    foreach (TestGuy_Manager::$modules as $module) {
		        $module->_failed($this, $fail);
		    }
		    throw $fail;
	    }

	    foreach (TestGuy_Manager::$modules as $module) {
	        $module->_after($this);
	    }
    }

	public function loadScenario() {
		$scenario = $this->scenario;
		if (file_exists($this->bootstrap)) require $this->bootstrap;
		require_once $this->testfile;
	}

    public function runStep(TestGuy_Step $step)
    {
	    if ($this->debug) $this->output->put("\n* ".$step->__toString());
	    if ($step->getName() == 'Comment') return;

	    foreach (TestGuy_Manager::$modules as $module) {
	        $module->_beforeStep($step);
	    }

	    $this->trace[] = $step;
		$action = $step->getAction();
	    $arguments = array_merge($step->getArguments());
	    if (!isset(TestGuy_Manager::$methods[$action])) {
		    $this->fail("Method $action not defined exist");
		    $this->stopped = true;
		    return;
	    }

	    $module = TestGuy_Manager::$modules[TestGuy_Manager::$methods[$action]];

        try {
            if (is_callable(array($module, $action))) {
                call_user_func_array(array($module, $action), $arguments);

            }
        } catch (PHPUnit_Framework_ExpectationFailedException $fail) {
            if ($module->_getDebugOutput() && $this->debug) $this->output->debug($module->_getDebugOutput());
            throw $fail;
        }

	    foreach (TestGuy_Manager::$modules as $module) {
	        $module->_afterStep($step);
	    }

        $output = $module->_getDebugOutput();
	    if ($output && $this->debug) $this->output->debug($output);
    }
	
	public function toString() {
	    return $this->scenario->getFeature().' ('.$this->getFileName().')';
	}

	public function getTrace() {
	    return $this->trace;
	}

	public function setOutput(TestGuy_Output $output) {
        $this->output = $output;
	}
}
