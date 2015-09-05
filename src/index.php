<?php
require_once __DIR__.'/../vendor/autoload.php';

use Tikomatic\Command;
use Symfony\Component\Console\Application;

$application = new Application('Tikomatic', '@package_version@');
$application->add(new Command\RebootCommand());
$application->add(new Command\UptimeCommand());
$application->add(new Command\HelloCommand());
$application->add(new Command\UpdateCommand());
$application->add(new Command\LicenseCommand());
$application->run();