<?php

namespace Tikomatic\Command;

use Tikomatic\Registry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PEAR2\Net\RouterOS;

class UserGroupCommand extends TikCommand
{
    protected function configure()
    {
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');
        
        parent::configure();
        $this
            ->setName('user:group')
            ->setDescription($translator->trans('Create/Read/Update/Delete user groups'))
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

        $data = $this->getResources($host, $username, $password);
        print_r( $data );
        //$output->writeln( $response );

    }

    protected function getResources($host, $username, $password) 
    {

        try {
            $client = new RouterOS\Client($host, $username, $password);
        } catch (Exception $e) {
            die('Unable to connect to the router.');
            //Inspect $e if you want to know details about the failure.
        }

        $responses = $client->sendSync(new RouterOS\Request('/user/group/print'));

        $data = [];
        $count = 0;
        foreach ($responses as $response) {
            if ($response->getType() === RouterOS\Response::TYPE_DATA) {
                foreach ($response as $name => $value) {
                    $data[$count][$name] = $value;
                }
            }
            $count++;
        }

        return $data;
        //
    }

}