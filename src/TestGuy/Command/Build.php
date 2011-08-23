<?php
/**
 * Author: davert
 * Date: 17.08.11
 *
 * Class TestGuy_Command_Build
 * Description:
 *
 */

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use \Symfony\Component\Console\Helper\DialogHelper;

class TestGuy_Command_Build extends TestGuy_Command_Base
{

	protected $template = <<<EOF
<?php
// This class was automatically generated by build task
// You can change it manually, but it will be overwritten on next build

/**
%s
**/


class %s extends %s
{


}


EOF;
    
    public function getDescription() {
        return 'Generates TestGuy.php class for all suites';
    }

    protected function configure()
    {
        $this->setDefinition(array(
            new \Symfony\Component\Console\Input\InputOption('silent', '', InputOption::VALUE_NONE, 'Don\'t ask for rebuild')
        ));
        parent::configure();
    }

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        if (!$input->getOption('silent')) {
            $dialog = new DialogHelper();
            $confirmed = $dialog->askConfirmation($output, "This will build TestGuy class in your project's lib/test dir. Do you want to proceed? (Y/n)");
            if (!$confirmed) return;
        }

        foreach ($this->suites as $suite => $settings) {

            TestGuy_Standalone_Manager::init($settings);

            $phpdoc = array();
            $methodCounter = 0;

            foreach (TestGuy_Manager::$modules as $modulename => $module) {
                $class = new ReflectionClass($modulename);
                $methods = $class->getMethods();
                $phpdoc[] = '';
                $phpdoc[] = 'Methods from ' . $modulename;
                foreach ($methods as $method) {
                    if (strpos($method->name, '_') === 0) continue;
                    if (!$method->isPublic()) continue;
                    $params = array();
                    foreach ($method->getParameters() as $param) {
                        if ($param->isOptional()) continue;
                        $params[] = '$' . $param->name;
                    }

                    $params = implode(', ', $params);
                    $phpdoc[] = '@method void ' . $method->name . '(' . $params . ')';
                    $methodCounter++;
                }
            }
            $contents = sprintf($this->template, implode("\r\n * ", $phpdoc), $settings['class_name'], 'BaseTestGuy');

            file_put_contents($file = $this->tests_path.'/'.$suite.'/'.$settings['class_name'].'.php', $contents);
            $output->writeln("$file generated sucessfully. $methodCounter methods added");
        }
    }
}
