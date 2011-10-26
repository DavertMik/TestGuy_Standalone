<?php
/**
 * Author: davert
 * Date: 26.10.11
 *
 * Class Install
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

class TestGuy_Command_Install extends \Symfony\Component\Console\Command\Command {

    

	public function execute(InputInterface $input, OutputInterface $output) {

        $dialog = new DialogHelper();
        $confirmed = $dialog->askConfirmation($output,
            "This will install all TestGuy dependencies through PEAR installer.\n"
            . "Symfony Components and Mink, will be installed.\n"
            . "Do you want to proceed? (Y/n)");
        if (!$confirmed) return;

        $output->writeln("Installing Symfony Components...");
        $output->write(shell_exec("pear channel-discover pear.symfony.com"));
        $output->write(shell_exec('pear install symfony2/Yaml'));
        $output->write(shell_exec('pear install symfony2/Finder'));

        $output->writeln("Installing Mink...");
        $output->write(shell_exec("pear channel-discover pear.behat.org"));
        $output->write(shell_exec("pear install behat/mink"));

        $output->writeln("Installaction complete. Init your new TestGuy suite calling the 'init' command");
    }


}
