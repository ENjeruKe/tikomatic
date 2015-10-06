<?php
use Tikomatic\Command\LicenseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ListCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new LicenseCommand());

        $command = $application->find('list');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp("/\n\s+list\s+Lists commands\n/", $commandTester->getDisplay());

        // ...
    }
}