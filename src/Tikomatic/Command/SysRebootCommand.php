<?php

namespace Tikomatic\Command;

use Tikomatic\Registry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\Table;

class SysRebootCommand extends TikCommand
{
    protected function configure()
    {
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');

        parent::configure();
        $this
            ->setName('sys:reboot')
            ->setDescription($translator->trans('Reboot Router'))
            ->addArgument(
                'action',
                InputArgument::IS_ARRAY,
                "now | in <time> | status | cancel"
            )
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

        //Check for subcommand action
        if ( !empty( $input->getArgument('action')[0] ) ) {
            $action = $input->getArgument('action')[0];
        } else {
            $action = '';
        }

        switch( $action ) {
            case 'status': //status of scheduled reboot
                break;
            case 'in': //delayed reboot, creates self deleting schedule
                if ( empty($input->getArgument('action')[1]) ) { 
                    $output->writeln( "Time missing: i.e. reboot in 10m" ); 
                    exit; 
                }
                $output->writeln( "TODO reboot in " . $input->getArgument('action')[1] );
                break;
            case 'cancel': //cancels scheduled reboot
                break;
            case 'now': //no prompt
                $output->writeln( "TODO reboot now " . $host );
                $this->doReboot($host, $username, $password);
                break;
            default: //prompt confirm reboot
                $ans = $this->askConfirm($input, $output);
                if ($ans !== true ) {
                    exit;
                }
                $output->writeln( "TODO reboot " . $host );
                $this->doReboot($host, $username, $password);
                
        } 

    }

    protected function doReboot($host, $username, $password) 
    {
        return true;
    }

}