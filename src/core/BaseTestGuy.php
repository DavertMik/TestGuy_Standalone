<?php
/**
 * Author: davert
 * Date: 01.06.11
 *
 * Class BaseTestGuy
 * Description:
 * 
 */
class BaseTestGuy  {
    public static $methods = array();

    /**
     * @var PHPUnit_Extensions_Story_Scenario
     */
    protected $scenario;

    public function __construct(TestGuy_Scenario $scenario) {
        $this->scenario = $scenario;

	    foreach (TestGuy_Manager::$modules as $module) {
			$module->_cleanup();
	    }
    }

	public function wantToTest($text) {
		$this->scenario->setFeature("test $text");
	}

	public function wantTo($text) {
		$this->scenario->setFeature($text);
	}

	public function expectTo($prediction) {
		$this->scenario->comment(array('expect to '.$prediction));
	}

	public function amGoingTo($argumentation) {
	    $this->scenario->comment(array('am going to '.$argumentation));
	}

    public function __call($method, $args) {
        if (!in_array($method, array_keys(TestGuy::$methods))) throw new Exception("Action $method not defined");
        if (0 === strpos($method,'see')) {
            $this->scenario->then(array_merge(array($method) ,$args));
        } elseif (0 === strpos($method,'am')) {
            $this->scenario->given(array_merge(array($method) ,$args));
        } else {
            $this->scenario->when(array_merge(array($method),$args));
        }
    }

}
