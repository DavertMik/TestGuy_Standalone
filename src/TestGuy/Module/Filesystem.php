<?php

class TestGuy_Module_Filesystem extends TestGuy_Module {

    protected $file = null;

    public function openFile($filename) {
        $this->file = file_get_contents($filename);
    }
    
    public function seeInFile($text) {
        PHPUnit_Framework_Assert::assertContains($text, $this->file,"text $text in currently opened file");
    }

    public function dontSeeInFile($text) {
        PHPUnit_Framework_Assert::assertNotContains($text, $this->file,"text $text in currently opened file");
    }
    
    public function findFile($filename, $path = '') {
        $path = getcwd().'/'.$path;
        $this->debug($path);

        $files = \Symfony\Component\Finder\Finder::create()->files()->name($filename)->in($path);
        foreach ($files as $file) {
            return $this->openFile($file);
        }
        PHPUnit_Framework_Assert::fail("$filename in $path");
    }
    
}