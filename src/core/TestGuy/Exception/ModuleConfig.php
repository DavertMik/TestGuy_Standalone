<?php
/**
 * Author: davert
 * Date: 14.10.11
 *
 * Class TestGuy_Exception_ModuleConfig
 * Description:
 * 
 */
 
class TestGuy_Exception_ModuleConfig extends Exception {
    
    public function __construct($module, $message) {
        $module = str_replace('TestGuy_Module_','',$module);
        parent::__construct($message);
        $this->message = $module." module is not configured!\n ". $this->message;
    }


}
