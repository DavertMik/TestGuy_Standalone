<?php

class TeseGuy_Module_Cli extends TestGuy_Module
{
    protected $output = '';

    protected function _cleanup()
    {
        $this->output = '';
    }
    
    public function amInPath($dir) {
        chdir($dir);
    }

    public function execute($command, $params = null) {
        if (is_array($params)) $params = implode(' ',$params);
        $this->output = shell_exec("$command $params");
    }

    public function seeInOutput($text) {
        PHPUnit_Framework_Assert::assertContains($text, $this->output);
    }

    public function dontSeeInOutput($text) {
        PHPUnit_Framework_Assert::assertNotContains($text, $this->output);
    }

}