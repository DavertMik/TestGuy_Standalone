<?php
/**
 * Author: davert
 * Date: 14.07.11
 *
 * Class TestGuy_Module
 * Description:
 * 
 */
 
abstract class TestGuy_Module {

	protected $debugStack = array();

	protected $storage = array();
    
    protected $config = array();
    
    public function _setConfig($config) {
        $this->config = $config;
    }

	// HOOK: on every TestGuy class initialization
	public function _cleanup()
	{
	}

	// HOOK: before every step
	public function _beforeStep(TestGuy_Step $step) {
	}

	// HOOK: after every  step
	public function _afterStep(TestGuy_Step $step) {
	}

	// HOOK: before scenario
	public function _before(TestGuy_TestCase $test) {
	}

	// HOOK: after scenario
	public function _after(TestGuy_TestCase $test) {
	}

	// HOOK: on fail
	public function _failed(TestGuy_TestCase $test, $fail) {
	}

	
	protected function debug($message) {
	    $this->debugStack[] = $message;
	}

	protected function debugSection($title, $message) {
		$this->debug("[$title] $message");
	}

	public function _clearDebugOutput() {
		$this->debugStack = array();
	}

	public function _getDebugOutput() {
		$debugStack = $this->debugStack;
		$this->_clearDebugOutput();
	    return $debugStack;
	}

	protected function assert($arguments, $not = false) {
		$not = $not ? 'Not' : '';
		$method = ucfirst(array_shift($arguments));
		if (($method === 'True') && $not) {
			$method = 'False';
			$not = '';
		}
		if (($method === 'False') && $not) {
			$method = 'True';
			$not = '';
		}

		call_user_func_array(array('PHPUnit_Framework_Assert', 'assert'.$not.$method), $arguments);
	}

	protected function assertNot($arguments) {
		$this->assert($arguments, true);
	}

}
