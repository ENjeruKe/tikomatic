<?php

namespace Tikomatic\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class RebootCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('reboot')
            ->setDescription('Reboot Remote Device')
            ->addArgument(
                'action',
                InputArgument::IS_ARRAY,
                "now | in <time> | status | cancel"
            )
            ->addOption(
                'conf',
                'c',
                InputOption::VALUE_REQUIRED,
                'Path to ini file containing host,username,password'
            )
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                'Hostname or IP Address'
            )
            ->addOption(
                'username',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Login username'
            )
            ->addOption(
                'password',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Login password'
            )
            ->addOption(
                'port',
                'P',
                InputOption::VALUE_OPTIONAL,
                'Port providing RouterOS API',
                8728
            )
            ->addOption(
                'ssl',
                's',
                InputOption::VALUE_NONE,
                'Try to use SSL/TLS for the connection'
            )
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        //Load options from ini file
        $conf = $input->getOption('conf');
        if ( file_exists($conf) ) {
            $iniconf = parse_ini_file($conf);

            if ( !$input->getOption('host') AND array_key_exists('host', $iniconf) ) {
                $input->setOption('host', $iniconf['host'] );
            }
            if ( !$input->getOption('username') AND array_key_exists('username', $iniconf) ) {
            $input->setOption('username', $iniconf['username'] );
            }
            if ( !$input->getOption('password') AND array_key_exists('password', $iniconf) ) {
                $input->setOption('password', $iniconf['password'] );
            }
        } else {
            echo "File not found: $conf\n";
            exit;
        }
        //end ini file


        //ask for host if not specified as option
        if ( !$host = $input->getOption('host') ) {
            $input->setOption('host', $this->askQuestion($input, $output, "Hostname:"));
        }

        //ask for username if not specified as option
        if ( !$host = $input->getOption('username') ) {
            $input->setOption('username', $this->askQuestion($input, $output, "Username:"));
        }

        //ask for password if not specified as option
        if ( !$password = $input->getOption('password') ) {
            $password = $this->askPassword($input, $output);
            $input->setOption('password', $password);
        }

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
                if ( empty($input->getArgument('action')[1]) ) { $output->writeln( "Time missing: i.e. reboot in 10m" ); exit; }
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

    protected function askConfirm($input, $output) 
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Confirm Reboot?', false);

        if (!$helper->ask($input, $output, $question)) {
            return false;
        }
        return true;
    }


    protected function askQuestion( $input, $output, $prompt, $default_answer=null ) 
    {
        $helper = $this->getHelper('question');

        $question = new Question($prompt, $default_answer);
        return $helper->ask($input, $output, $question);  
    }

    protected function askPassword( $input, $output) 
    {
        $helper = $this->getHelper('question');

        $question = new Question('Password:', null);
        //setHidden does not work with Phar
        //$question->setHidden(true);
        //$question->setHiddenFallback(false);
        return $helper->ask($input, $output, $question);  
    }
}