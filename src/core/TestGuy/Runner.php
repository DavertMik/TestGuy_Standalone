<?php
/**
 * Author: davert
 * Date: 21.07.11
 *
 * Class TestGuy_Runner
 * Description:
 * 
 */
 
class TestGuy_Runner extends PHPUnit_TextUI_TestRunner {


	public function doRun(PHPUnit_Framework_Test $suite, array $arguments = array())
	{
	    $this->handleConfiguration($arguments);

	    if (is_integer($arguments['repeat'])) {
	        $suite = new PHPUnit_Extensions_RepeatedTest(
	          $suite,
	          $arguments['repeat'],
	          $arguments['filter'],
	          $arguments['groups'],
	          $arguments['excludeGroups'],
	          $arguments['processIsolation']
	        );
	    }

	    $result = $this->createTestResult();

	    if (!$arguments['convertErrorsToExceptions']) {
	        $result->convertErrorsToExceptions(FALSE);
	    }

	    if (!$arguments['convertNoticesToExceptions']) {
	        PHPUnit_Framework_Error_Notice::$enabled = FALSE;
	    }

	    if (!$arguments['convertWarningsToExceptions']) {
	        PHPUnit_Framework_Error_Warning::$enabled = FALSE;
	    }

	    if ($arguments['stopOnError']) {
	        $result->stopOnError(TRUE);
	    }

	    if ($arguments['stopOnFailure']) {
	        $result->stopOnFailure(TRUE);
	    }

	    if ($arguments['stopOnIncomplete']) {
	        $result->stopOnIncomplete(TRUE);
	    }

	    if ($arguments['stopOnSkipped']) {
	        $result->stopOnSkipped(TRUE);
	    }

	    if ($this->printer === NULL) {
	        if (isset($arguments['printer']) &&
	            $arguments['printer'] instanceof PHPUnit_Util_Printer) {
	            $this->printer = $arguments['printer'];
	        } else {
	            $this->printer = new TestGuy_ResultPrinter_UI(
	              NULL,
	              $arguments['verbose'],
	              $arguments['colors'],
	              $arguments['debug']
	            );
	        }
	    }

	    if (isset($arguments['report'])) {
		    if ($arguments['report']) $this->printer = new TestGuy_ResultPrinter_Report();
	    }

		if (!$this->printer instanceof TestGuy_ResultPrinter_Report) {
			$this->printer->write(
			  TestGuy_Manager::versionString().
			  'Powered by ' . PHPUnit_Runner_Version::getVersionString() . "\n\n"
			);
		}

	    if (isset($arguments['html'])) {
	        if ($arguments['html']) $result->addListener(new TestGuy_ResultPrinter_HTML($arguments['html']));
	    }

	    foreach ($arguments['listeners'] as $listener) {
	        $result->addListener($listener);
	    }

	    $result->addListener($this->printer);

	    if ($arguments['strict']) {
	        $result->strictMode(TRUE);
	    }

	    $suite->run(
	      $result,
	      $arguments['filter'],
	      $arguments['groups'],
	      $arguments['excludeGroups'],
	      $arguments['processIsolation']
	    );

	    unset($suite);
	    $result->flushListeners();

	    if ($this->printer instanceof PHPUnit_TextUI_ResultPrinter) {
	        $this->printer->printResult($result);
	    }

	    $this->pause($arguments['wait']);

	    return $result;
	}


}
