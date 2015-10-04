<?php

namespace Tikomatic\Command;

use Tikomatic\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Helper\Table;

class TikCommand extends Command
{
    protected function configure()
    {
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');

        $this
            ->addOption(
                'conf',
                'c',
                InputOption::VALUE_REQUIRED,
                $translator->trans('Path to ini file containing host,username,password')
            )
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                $translator->trans('Hostname or IP Address')
            )
            ->addOption(
                'username',
                'u',
                InputOption::VALUE_OPTIONAL,
                $translator->trans('Login username')
            )
            ->addOption(
                'password',
                'p',
                InputOption::VALUE_OPTIONAL,
                $translator->trans('Login password')
            )
            ->addOption(
                'port',
                'P',
                InputOption::VALUE_OPTIONAL,
                $translator->trans('Port providing RouterOS API'),
                8728
            )
            ->addOption(
                'ssl',
                's',
                InputOption::VALUE_NONE,
                $translator->trans('Connect to API using SSL/TLS')
            )
            ->addOption(
                    'format',
                    'f',
                    InputOption::VALUE_REQUIRED,
                    $translator->trans('Output Format (*table,xml,json,csv,tsv)')
            )
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');

        //Load options from ini file
        $conf = $input->getOption('conf');

        if ( !empty($conf) ) {
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
                echo $translator->trans("File not found").": $conf" ."\n";
                exit;
            }

        } 
        //end ini file


        //ask for host if not specified as option
        if ( !$host = $input->getOption('host') ) {
            $input->setOption('host', $this->askQuestion($input, $output, $translator->trans("Hostname").':'));
        }

        //ask for username if not specified as option
        if ( !$host = $input->getOption('username') ) {
            $input->setOption(
                'username', 
                $this->askQuestion(
                    $input, 
                    $output, 
                    $translator->trans("Username").':'
                )
            );
        }

        //ask for password if not specified as option
        if ( !$password = $input->getOption('password') ) {
            $password = $this->askPassword($input, $output);
            $input->setOption('password', $password);
        }

    }

    protected function outFormatter( InputInterface $input, OutputInterface $output, $data ) {
        switch ( $input->getOption('format') ) {
            case 'csv':
                $this->outCsv( $input, $output, $data );
                break;
            case 'json':
                $this->outJson( $input, $output, $data );
                break;
            case 'xml':
                $this->outXml( $input, $output, $data );
                break;
            case 'table':
            default:
                $this->outTable( $input, $output, $data );
        }
    }

    protected function outTable( InputInterface $input, OutputInterface $output, $data ) {
        $table = new Table($output);
        $table
            ->setHeaders(array_keys($data[0]))
            ->setRows($data)
        ;
        $table->render();
    }

    protected function outJson( InputInterface $input, OutputInterface $output, $data ) {
        echo json_encode($data);
    }

    protected function outCsv( InputInterface $input, OutputInterface $output, $data ) {

        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($data[0]));
        foreach ($data as $item) {
            fputcsv($out, $item);
        }
        fclose($out);
    }

    protected function outXml( InputInterface $input, OutputInterface $output, $data ) {
        $wrap = [ 'ipaddress' => $data ];
        $xml = new \SimpleXMLElement('<tikomatic/>');
        array_walk_recursive($wrap, array ($xml, 'addChild'));
        print $xml->asXML();
    }

    protected function askConfirm($input, $output) 
    {
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion( $translator->trans('Confirm Reboot?'), false);

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
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');

        $helper = $this->getHelper('question');

        $question = new Question( $translator->trans('Password').':', null);
        //setHidden does not work with Phar
        //$question->setHidden(true);
        //$question->setHiddenFallback(false);
        return $helper->ask($input, $output, $question);  
    }
}