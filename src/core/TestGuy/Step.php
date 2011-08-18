<?php
/**
 * Author: davert
 * Date: 15.07.11
 *
 * Class TestGuy_Step
 * Description:
 * 
 */
 
abstract class TestGuy_Step {

	/**
	 * @var    string
	 */
	protected $action;

	/**
	 * @var    array
	 */
	protected $arguments;

	/**
	 * Constructor.
	 *
	 * @param  array $arguments
	 */
	public function __construct(array $arguments)
	{
	    $this->action    = array_shift($arguments);
	    $this->arguments = $arguments;
	}

	/**
	 * Returns this step's action.
	 *
	 * @return string
	 */
	public function getAction()
	{
	    return $this->action;
	}

	/**
	 * Returns this step's arguments.
	 *
	 * @param  boolean $asString
	 * @return array|string
	 */
	public function getArguments($asString = FALSE)
	{
	    if (!$asString) {
	        return $this->arguments;
	    } else {
	        switch (count($this->arguments)) {
	            case 0: {
	                return '';
	            }
	            break;

	            case 1: {
	                return '"'.$this->arguments[0].'"';
	            }
	            break;

	            default: {
	                return json_encode($this->arguments);
	            }
	        }
	    }
	}

	abstract public function getName();
	
	public function __toString() {
	    return "I ".$this->humanize($this->getAction()).' '.$this->clean($this->getArguments(true));
	}

	public function getHumanizedAction() {
	    return $this->humanize($this->getAction());
	}

	protected function clean($text) {
	    return str_replace('\/','/', $text);
	}
	
	protected function humanize($text)
	{
		$text = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\\1 \\2', $text);
		$text = preg_replace('/([a-z\d])([A-Z])/', '\\1 \\2', $text);
		$text = preg_replace('~\bdont\b~','don\'t', $text);
		return strtolower($text);

	}

}
