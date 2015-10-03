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

$application->add(new Command\ExportCommand());
$application->add(new Command\UserCommand());
$application->add(new Command\UserActiveCommand());
$application->add(new Command\UserSshkeysCommand());
$application->add(new Command\UserSshkeysPrivateCommand());
$application->add(new Command\UserGroupCommand());
$application->add(new Command\RebootCommand());

$application->add(new Command\IpAddressCommand());
$application->add(new Command\IpFwAddressListCommand());
$application->add(new Command\ResourcesCommand());
$application->add(new Command\VersionCommand());
$application->add(new Command\CpuloadCommand());
$application->add(new Command\UptimeCommand());
$application->add(new Command\UpdateCommand());
$application->add(new Command\LicenseCommand());
$application->run();