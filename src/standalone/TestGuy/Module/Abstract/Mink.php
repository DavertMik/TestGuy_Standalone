<?php
/**
 * 
 * Author: davert
 * Date: 18.08.11
 * 
 */
 
abstract class TestGuy_Module_Abstract_Mink extends TestGuy_Module
{
    /**
     * @var \Behat\Mink\Session
     */
    protected $session;
    private $content;

    public function amOnPage($page)
    {

        $this->session->visit($this->config['start'].$page);
    }

    public function dontSee($text, $selector = null) {
        $res = $this->proceedSee($text, $selector);
        $this->assertNot($res);
    }

    public function see($text, $selector = null) {
        $res = $this->proceedSee($text, $selector);
        $this->assert($res);
    }

    protected function proceedSee($text, $selector = null) {
        if ($selector) {
            $nodes = $this->session->getPage()->findAll('css', $selector);
		    $values = array();
		    foreach ($nodes as $node) {
		        $values[] = trim($node->getText());
		    }
			return array('contains', $text, $values, "'$selector' selector in " . $this->session->getPage()->getContent().'. For more details look for page snapshot in the log directory');
        }

        $response = $this->session->getPage()->getContent();
        if (strpos($response, '<!DOCTYPE')!==false) {
            $response = array();
            $title = $this->session->getPage()->find('css','title');
            if ($title) $response['title'] = trim($title->getText());

            $h1 = $this->session->getPage()->find('css','h1');
            if ($h1 && is_object($title)) $response['h1'] = trim($h1->getText());

            $response['uri'] = $this->session->getCurrentUrl();
            if ($this->session->getStatusCode()) $response['responseCode'] = $this->session->getStatusCode();
            $response = json_encode($response);
            $response = 'html page response '.$response;
        }
        return array('contains', $text, strip_tags($this->session->getPage()->getContent()), "'$text' in ".$response.'. For more details look for page snapshot in the log directory');
    }

    public function click($link) {
        $url = $this->session->getCurrentUrl();
        $this->session->getPage()->clickLink($link);
        if ($this->session->getCurrentUrl() != $url) {
            $this->debug('moved to page '. $this->session->getCurrentUrl());
        }
    }
    
    public function reloadPage() {
        $this->session->reload();
    }
    
    public function moveBack() {
        $this->session->back();
        $this->debug($this->session->getCurrentUrl());
    }

    public function moveForward() {
        $this->session->forward();
        $this->debug($this->session->getCurrentUrl());
    }

    public function fillField($field, $value)
    {
        $this->session->getPage()->fillField($field, $value);
    }

    public function fillFields(TableNode $fields)
    {
        foreach ($fields->getRowsHash() as $field => $value) {
            $this->fillField($field, $value);
        }
    }

    public function selectOption($select, $option)
    {
        $this->session->getPage()->selectFieldOption($select, $option);
    }

    public function checkOption($option)
    {
        $this->session->getPage()->checkField($option);
    }

    public function uncheckOption($option)
    {
        $this->session->getPage()->uncheckField($option);
    }

    public function attachFileToField($field, $path)
    {
        $this->session->getPage()->attachFileToField($field, $path);
    }

    public function seeInCurrentAddress($uri) {
        PHPUnit_Framework_Assert::assertContains($uri, $this->session->getCurrentUrl(),'');
    }
    
    public function seeCheckboxIsChecked($checkbox) {
       $node = $this->session->getPage()->findField($checkbox);
        if (!$node) return PHPUnit_Framework_Assert::fail(", checkbox not found");
        PHPUnit_Framework_Assert::assertTrue($node->isChecked);
    }

    public function dontSeeCheckboxIsChecked($checkbox) {
        $node = $this->session->getPage()->findField($checkbox);
         if (!$node) return PHPUnit_Framework_Assert::fail(", checkbox not found");
         PHPUnit_Framework_Assert::assertFalse($node->isChecked);
    }
    
    public function seeInField($field, $value) {
        $node  = $this->session->getPage()->findField($field);
        if (!$node) return PHPUnit_Framework_Assert::fail(", field not found");
        PHPUnit_Framework_Assert::assertEquals($value, $node->getValue());
    }

    public function dontSeeInField($field, $value) {
        $node  = $this->session->getPage()->findField($field);
        if (!$node) return PHPUnit_Framework_Assert::fail(", field not found");
        PHPUnit_Framework_Assert::assertNotEquals($value, $node->getValue());

    }

}
