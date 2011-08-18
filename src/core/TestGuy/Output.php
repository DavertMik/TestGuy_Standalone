<?php
/**
 * Author: davert
 * Date: 06.07.11
 *
 * Class TestGuy_Output
 * Description:
 * 
 */
 
class TestGuy_Output {

	protected static $instance = null;

	protected $printer = null;
    protected $colors = true;

	function __construct($printer = null, $colors = true) {
	    $this->printer = $printer;
	    $this->colors = $colors;
	}

	public function setPrinter(PHPUnit_Util_Printer $printer) {
	    $this->printer = $printer;
	}

	public function put($message) {
		if (!$this->printer) return;

        $message = $this->colors ? $this->colorize($message) : $this->naturalize($message);
		$message = $this->clean($message);
		$this->write($message);

	}

	private function write($text)
	{
		if (!$this->printer) return;
		$this->printer->write($text);
	}

    protected function naturalize($message)
    {
		$message = str_replace(array('[[',']]','(%','%)','((','))','(!','!)'), array('','','','','','','',''), $message);
		return $message;
    }

	protected function colorize($message) {
		// magent colors
		$message = str_replace(array('[[',']]'), array("\033[35;1m","\033[0m"), $message);
		$message = str_replace(array('(%','%)'), array("\033[45;37m","\033[0m"), $message);
		// grey
		$message = str_replace(array('((','))'), array("\033[37;1m","\033[0m"), $message);
		$message = str_replace(array('(!','!)'), array("\033[41;37m","\033[0m"), $message);
		return $message;
	}

	protected function clean($message)
	{
		// clear json serialization
		$message = str_replace('\/','/', $message);
		return $message;
	}

	public function writeln($message) {

		$this->put("\n= ".$message);

	}

	public function debug($message) {
		if (is_array($message)) $message = implode("\n=> ", $message);
        $this->colors ? $this->write("\033[36m\n=> ".$message."\033[0m") : $this->write("=> ".$message) ;
	}

}
