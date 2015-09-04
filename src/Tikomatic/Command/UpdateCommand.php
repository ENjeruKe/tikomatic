<?php

namespace Tikomatic\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{
    const MANIFEST_FILE = 'http://jcutrer.com/tikomatic/manifest.json';

    protected function configure()
    {
        $this
            ->setName('update')
            ->setDescription('Updates tikomatic to the latest version')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Update is currently broken, debug info follows');
        $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));

        $output->writeln('Looking for updates...');

        $currentVersion = $this->getApplication()->getVersion();

        if ($manager->update($currentVersion, true)) {
            $output->writeln('<info>Updated to latest version</info>');
        } else {
            $output->writeln('<comment>Already up-to-date.</comment>');
        }
    }
}