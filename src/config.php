<?php
use Tikomatic\Registry;
use Tikomatic\Config;

$config = new Config();
$registry = Registry::getInstance();
$registry->set('config', $config);
