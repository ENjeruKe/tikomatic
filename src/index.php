<?php
require_once 'bootstrap.php';

use Tikomatic\Command;
use Tikomatic\Registry;
use Tikomatic\Config;
use Symfony\Component\Console\Application;


//Create Application
$application = new Application('Tikomatic', '@package_version@');

// /export
$application->add(new Command\ExportCommand());

// /user
$application->add(new Command\UserCommand());
$application->add(new Command\UserGroupCommand());
$application->add(new Command\UserActiveCommand());
$application->add(new Command\UserSshkeysCommand());
$application->add(new Command\UserSshkeysPrivateCommand());

// /ip
$application->add(new Command\IpAddressCommand());

// /ip/firewall
$application->add(new Command\IpFwAddressListCommand());
$application->add(new Command\IpFwConnectionCommand());

// /system
$application->add(new Command\SysHistoryCommand());
$application->add(new Command\SysResourcesCommand());
$application->add(new Command\SysVersionCommand());
$application->add(new Command\SysCpuloadCommand());
$application->add(new Command\SysUptimeCommand());
$application->add(new Command\SysRebootCommand());

// other commands
$application->add(new Command\UpdateCommand());
$application->add(new Command\LicenseCommand());
$application->run();