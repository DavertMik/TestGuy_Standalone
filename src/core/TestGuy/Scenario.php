<?php
/**
 * Author: davert
 * Date: 15.07.11
 *
 * Class TestGuy_Scenario
 * Description:
 * 
 */
 
class TestGuy_Scenario {
    /**
     * @var    PHPUnit_Extensions_Story_TestCase
     */
    protected $test;

    /**
     * @var    array
     */
    protected $steps = array();

    /**
     * @var    string
     */
	protected $feature;

	public $world;

    /**
     * Constructor.
     *
     * @param  PHPUnit_Extensions_Story_TestCase $caller
     */
    public function __construct(TestGuy_TestCase $test)
    {
		$this->test = $test;
    }


	public function setFeature($feature) {
	    $this->feature = strtolower($feature);
	}
    /**
     * Adds a "Given" step to the scenario.
     *
     * @param  array $arguments
     * @return PHPUnit_Extensions_Story_TestCase
     */
    public function given($arguments)
    {
        return $this->addStep(new TestGuy_Step_Condition($arguments));
    }

    /**
     * Adds a "When" step to the scenario.
     *
     * @param  array $arguments
     * @return PHPUnit_Extensions_Story_TestCase
     */
    public function when($arguments)
    {
        return $this->addStep(new TestGuy_Step_Action($arguments));
    }

    /**
     * Adds a "Then" step to the scenario.
     *
     * @param  array $arguments
     * @return PHPUnit_Extensions_Story_TestCase
     */
    public function then($arguments)
    {
        return $this->addStep(new TestGuy_Step_Assertion($arguments));
    }

    /**
     * Runs this scenario.
     *
     * @param  array $world
     */
    public function run()
    {
        foreach ($this->steps as $step)
        {
            $this->test->runStep($step);
        }
    }

    /**
     * Adds a step to the scenario.
     *
     * @param  PHPUnit_Extensions_Story_Step $step
     * @return PHPUnit_Extensions_Story_TestCase
     */
    protected function addStep(TestGuy_Step $step)
    {
        $this->steps[] = $step;

        return $this->test;
    }

    /**
     * Returns the steps of this scenario.
     *
     * @return array
     */
    public function getSteps()
    {
        return $this->steps;
    }

	public function getFeature() {
	    return $this->feature;
	}

	public function comment($comment) {
		$this->addStep(new TestGuy_Step_Comment($comment));
	}

}
