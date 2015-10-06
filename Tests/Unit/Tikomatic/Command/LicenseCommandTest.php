<?php
use Tikomatic\Command\LicenseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class LicenseCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new LicenseCommand());

        $command = $application->find('license');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/TIKOMATIC LICENSE/', $commandTester->getDisplay());

        // ...
    }
}