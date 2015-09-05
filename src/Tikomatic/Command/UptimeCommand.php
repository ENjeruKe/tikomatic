<?php

namespace Tikomatic\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class UptimeCommand extends TikCommand
{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('uptime')
            ->setDescription('Get uptime of remote device')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $host = $input->getOption('host');
        $username = $input->getOption('username');
        $password = $input->getOption('password');

        //Debug: print received options
        if ($output->isDebug()) {
            $output->writeln( "host=".$host );
            $output->writeln( "username=".$username );
            $output->writeln( "password=".$password );
        }


        $output->writeln( "TODO get uptime of " . $host );
        $this->getUptime($host, $username, $password);

    }

    protected function getUptime($host, $username, $password) 
    {
        return true;
    }

}