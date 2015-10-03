<?php

namespace Tikomatic\Command;

use Tikomatic\Registry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;
use PEAR2\Net\RouterOS;

class IpAddressCommand extends TikCommand
{
    protected function configure()
    {
        $registry = Registry::getInstance();
        $translator = $registry->get('translator');
        
        parent::configure();
        $this
            ->setName('ip:address')
            ->setDescription($translator->trans('Work with /ip address(es)'))
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

        $data = $this->getIpAddress($host, $username, $password);
        switch ( $input->getOption('format') ) {
            case 'csv':
                $this->outCsv( $data );
                break;
            case 'json':
                $this->outJson( $data );
                break;
            case 'xml':
                $this->outXml( $data );
                break;
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

    protected function outJson( $data ) {
        echo json_encode($data);
    }

    protected function outCsv( $data ) {

        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($data[0]));
        foreach ($data as $item) {
            fputcsv($out, $item);
        }
        fclose($out);
    }

    protected function outXml($data) {
        $wrap = [ 'ipaddress' => $data ];
        $xml = new \SimpleXMLElement('<tikomatic/>');
        array_walk_recursive($wrap, array ($xml, 'addChild'));
        print $xml->asXML();
    }

    protected function getIpAddress($host, $username, $password) 
    {

        try {
            $client = new RouterOS\Client($host, $username, $password);
        } catch (Exception $e) {
            die('Unable to connect to the router.');
            //Inspect $e if you want to know details about the failure.
        }

        $responses = $client->sendSync(new RouterOS\Request('/ip/address/print'));

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
    }

}