<?php
define('APP_BASEPATH', __DIR__ );

require_once __DIR__.'/../vendor/autoload.php';
require_once 'config.php';
require_once 'i18n.php';


use Tikomatic\Command;
use Tikomatic\Registry;
use Tikomatic\Config;
use Symfony\Component\Console\Application;


//Create Application
$application = new Application('Tikomatic', '@package_version@');

$application->add(new Command\RebootCommand());

$application->add(new Command\ResourcesCommand());
$application->add(new Command\VersionCommand());
$application->add(new Command\CpuloadCommand());
$application->add(new Command\UptimeCommand());

$application->add(new Command\HelloCommand());
$application->add(new Command\UpdateCommand());
$application->add(new Command\LicenseCommand());
$application->run();