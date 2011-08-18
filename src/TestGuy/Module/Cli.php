<?php

class TestGuy_Module_Cli extends TestGuy_Module
{
    protected $output = '';

    public function _cleanup()
    {
        $this->output = '';
    }
    
    public function amInPath($dir) {
        chdir($dir);
    }

    public function execute($command) {
        $this->output = shell_exec("$command");
        $this->debug($this->output);
    }

    public function seeInOutput($text) {

        PHPUnit_Framework_Assert::assertContains($text, $this->output);
    }

    public function dontSeeInOutput($text) {
        $this->debug($this->output);
        PHPUnit_Framework_Assert::assertNotContains($text, $this->output);
    }

}