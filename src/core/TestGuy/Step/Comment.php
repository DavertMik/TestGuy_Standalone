<?php
/**
 * Author: davert
 * Date: 15.07.11
 *
 * Class TestGuy_Step_Action
 * Description:
 * 
 */
 
class TestGuy_Step_Comment extends TestGuy_Step {

	public function getName() {
	    return 'Comment';
	}

	public function __toString() {
	    return "\n((I ".$this->humanize($this->getAction()).' '.$this->getArguments(true)."))";
	}

}
