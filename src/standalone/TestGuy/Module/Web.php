<?php
/**
 * Author: davert
 * Date: 23.08.11
 *
 * Class Mink_Goutte
 * Description:
 * 
 */
 
class TestGuy_Module_Web extends TestGuy_Module_Abstract_Mink {

    protected $requiredFields = array('start', 'log');

    public function _cleanup() {
        $zendOptions = array('httpversion' => '1.1', 'useragent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0a2) Gecko/20110613 Firefox/6.0a2');
        if (isset($this->config['zend'])) array_merge($this->config['zend'], $zendOptions);
        $mink = new \Behat\Mink\Mink(array('primary' => new \Behat\Mink\Session(new \Behat\Mink\Driver\GoutteDriver(new Goutte\Client($zendOptions)))));
        $this->session = $mink->getSession('primary');
    }

    public function submitForm($selector, $params) {

        $form = $this->session->getPage()->find('css',$selector);
	    $fields = $this->session->getPage()->findAll('css', $selector.' input');
	    $url = '';
	    foreach ($fields as $field) {
		    if ($field->getAttribute('type') == 'checkbox') continue;
		    if ($field->getAttribute('type') == 'radio') continue;
		    $url .= sprintf('%s=%s',$field->getAttribute('name'), $field->getAttribute('value')).'&';
	    }

	    $fields = $this->session->getPage()->findAll('css', $selector.' textarea');
	    foreach ($fields as $field) {
		    $url .= sprintf('%s=%s',$field->getAttribute('name'), $field->getText()).'&';
	    }

	    $fields = $this->session->getPage()->findAll('css', $selector.' select');
	    foreach ($fields as $field) {
            $url .= sprintf('%s=%s',$field->getAttribute('name'), $field->getValue()).'&';
	    }

		$url .= '&'.http_build_query($params);
	    parse_str($url, $params);
        $url = $form->getAttribute('action');
        $method = $form->getAttribute('method');


        $this->call($url, $method, $params);
    }

    public function sendAjaxPostRequest($uri, $params) {
        $this->session->setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        $this->call($uri, 'post', $params);
        $this->debug($this->session->getPage()->getContent());
    }


    public function sendAjaxGetRequest($uri, $params) {
        $this->session->setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        $this->call($uri, 'get', $params);
        $this->debug($this->session->getPage()->getContent());
    }


	protected function call($uri, $method = 'get', $params = array())
	{
        $browser = $this->session->getDriver()->getClient();
//        $uri = $browser->getAbsoluteUri($uri);

    	$this->debug('Request ('.$method.'): '.$uri.' '. json_encode($params));
		$browser->request($method, $uri, $params);

		$this->debug('Response code: '.$this->session->getStatusCode());
	}

	public function _failed(TestGuy_TestCase $test, $fail) {
		file_put_contents($this->config['log'].'/'.$test->getFileName().'.page.debug.html', $this->session->getPage()->getContent());

	}
    
}
