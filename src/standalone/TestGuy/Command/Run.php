<?php
/**
 * Author: davert
 * Date: 17.08.11
 *
 * Class Run
 * Description: starts the tests
 * 
 */

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TestGuy_Command_Run extends TestGuy_Command_Base {

    protected function configure()
    {
        $this->setDefinition(array(

            new \Symfony\Component\Console\Input\InputArgument('suite', InputArgument::OPTIONAL,'suite to be tested'),
            new \Symfony\Component\Console\Input\InputArgument('test', InputArgument::OPTIONAL,'test to be run'),

            new \Symfony\Component\Console\Input\InputOption('report', '', InputOption::VALUE_NONE, 'Show output in compact style'),
            new \Symfony\Component\Console\Input\InputOption('html', '', InputOption::VALUE_NONE, 'Generate html with results'),
            new \Symfony\Component\Console\Input\InputOption('colors', '', InputOption::VALUE_NONE, 'Use colors in output'),
            new \Symfony\Component\Console\Input\InputOption('debug', '', InputOption::VALUE_NONE, 'Show debug and scenario output')
        ));
        parent::configure();
    }

    protected  function prepareOptions(InputInterface $input) {
        $options = array();
        foreach ($input->getOptions() as $option => $value) {

            if (isset($this->config['options'][$option])) {
                if (!$value && $this->config['options'][$option]) $value = $this->config['options'][$option];
            }

            // no colors on windows
            if ($option == 'colors' && !$input->getOption('colors') && strtoupper(substr(PHP_OS, 0,3) == 'WIN')) {
                $value = false;
            }
            $options[$option] = $value;
        }

        if ($input->getArgument('test')) $options['debug'] = true;
        if ($options['html']) $options['html'] = $this->config['paths']['output'].'/result.html';

        return $options;
    }

    public function getDescription() {
        return 'Runs the test suites';
    }

	public function execute(InputInterface $input, OutputInterface $output) {

        ini_set('memory_limit', isset($this->config['memory_limit']) ? $this->config['memory_limit'] : '1024M');

        $options = $this->prepareOptions($input);

        if ($suite = $input->getArgument('suite')) $this->suites = array($suite => $this->suites[$suite]);

        $runner = new TestGuy_Runner();
        foreach ($this->suites as $suite => $settings) {
            $class = $settings['suite_class'];
            if (!class_exists($class)) throw new Exception("Suite class for $suite not found");

            if (file_exists($testguy = sprintf('%s/%s/%s.php', $this->tests_path, $suite, $settings['class_name']))) {
                require_once $testguy;
            }

            if (!class_exists($settings['class_name'])) throw new Exception("TestGuy class {$settings['class_name']} not found in $testguy");
            
            $output->writeln("Starting $suite...");

            TestGuy_Standalone_Manager::init($settings);

            $testManager = new TestGuy_Standalone_Manager(new $class, $options['debug']);
            if (isset($settings['bootstrap'])) $testManager->setBootstrtap($settings['bootstrap']);

            if ($test = $input->getArgument('test')) {
                $output->writeln($test);
                $testManager->loadTest($test, $this->tests_path.'/'.$suite);
            } else {
                $testManager->loadTests($this->tests_path.'/'.$suite);
            }

            $suite = new PHPUnit_Framework_TestSuite();
            $suite->addTestSuite($testManager->getCurrentSuite());
        }

        $result = $runner->doRun($suite, array_merge(array('convertErrorsToExceptions' => false), $options));

        if ($result->failureCount()) exit(1);
    }
}
