<?php

namespace Tikomatic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LicenseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('license')
            ->setDescription('Display Software License')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('TODO output contents of phar//LICENSE');
    }
}