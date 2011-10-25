<?php
/**
 * Author: davert
 * Date: 25.10.11
 *
 * Class Module
 * Description:
 * 
 */
 
class TestGuy_Exception_Module extends Exception {

    protected $module;
    
    public function __construct($module, $message) {
        $module = str_replace('TestGuy_Module_','',$module);
        parent::__construct($message);
        $this->message = '(Exception in '.$this->module.') ' . $this->message;
    }

}
