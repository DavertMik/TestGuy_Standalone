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
     * @var    TestGuy_TestCase
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
     * @param  TestGuy_TestCase $test
     */
    public function __construct(TestGuy_TestCase $test)
    {
		$this->test = $test;
    }


	public function setFeature($feature) {
	    $this->feature = strtolower($feature);
	}

    public function given($arguments)
    {
        return $this->addStep(new TestGuy_Step_Condition($arguments));
    }

    public function when($arguments)
    {
        return $this->addStep(new TestGuy_Step_Action($arguments));
    }

    public function then($arguments)
    {
        return $this->addStep(new TestGuy_Step_Assertion($arguments));
    }

    public function run()
    {
        foreach ($this->steps as $step)
        {
            $this->test->runStep($step);
        }
    }

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
