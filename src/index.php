<?php
require_once __DIR__.'/../vendor/autoload.php';

use Tikomatic\Command;
use Symfony\Component\Console\Application;

$application = new Application('Tikomatic', '@package_version@');
$application->add(new Command\HelloCommand());
$application->run();