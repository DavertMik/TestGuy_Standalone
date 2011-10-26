<?php
/**
 * Author: davert
 * Date: 26.10.11
 *
 * Class Init
 * Description:
 * 
 */
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\DialogHelper;
use \Symfony\Component\Yaml\Yaml;

class TestGuy_Command_Init extends \Symfony\Component\Console\Command\Command {

    public function execute(InputInterface $input, OutputInterface $output) {

        if (file_exists('testguy.dist.yml')) {
            $output->writeln("This directory already contains testguy.dist.yml. Project already initialized here");
            return;
        }


        $basicConfig = array(
            'paths' => array(
                'tests' => 'tests/testguy',
                'output' => 'tests/log',
            ),
            'options' => array(
                'colors' => true,
                'memory_limit' => '1024M'
            )
        );

        $str = Yaml::dump($basicConfig, 6);
        file_put_contents('testguy.dist.yml', $str);
        
        $output->writeln("File testguy.dist.yml written - contains TestGuy configuration");

        @mkdir('tests');
        @mkdir('tests/testguy');
        @mkdir('tests/log');
        @mkdir('tests/testguy/app');
        
        $suiteConfig = array(
            'app' => array(
                'class_name' => 'TestGuy',
                'suite_class' => 'PHPUnit_Framework_TestSuite',
                'modules' => array('Web', 'DbPopulator'),
                'Web' => array(
                    'start' => 'http://localhost/myapp/ # replace with url for app you want to test',
                    'log' => 'tests/log # path is used to store page snapshots'
                ),
                'DbPopulator' => array(
                    'dump' => '',
                    'dsn' => '',
                    'user' => '',
                    'password' => ''
                )
            )
        );

        $str = "# This is your test suite configuration.\n# Default suite is called 'app' and tests are stored in tests/testguy/app directory\n";
        $str .= "# Default modules are: \n#* Web - testing web sites with Mink, \n#* DbPoplulator - reinitializing database after each test passed\n";
        $str .= "# This modules require additional configuration.\n";
        $str .= "# To learn more about Testguy modules visit: https://github.com/DavertMik/TestGuy_Modules\n\n";
        $str .= Yaml::dump($suiteConfig, 6);
        file_put_contents('tests/testguy/suites.yml', $str);
        
        $output->writeln("tests/testguy/suites.yml written - contains configuration for test suites");
        $output->writeln("Review this configuration file and activate modules you need");
        $output->writeln("To complete initialization run 'build' command");
        
    }
}