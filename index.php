<?php

const ROOT_DIR = __DIR__ . '/';
const CORE_DIR = ROOT_DIR  . 'Core/';
const SERVICE_DIR = ROOT_DIR  . 'Service/';
const CACHE_DIR = ROOT_DIR . 'Cache/';
const GIT_DIR = ROOT_DIR . '.git/';
const VERSION = '0.1.4';

require_once ROOT_DIR . 'vendor/autoload.php';

\Core\Components\Config::setConfig(require ROOT_DIR . 'config.php');

// Magic!
new \Core\Bootstrap;
