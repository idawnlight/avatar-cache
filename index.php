<?php

const ROOT_DIR = __DIR__ . '/';
const CORE_DIR = ROOT_DIR  . 'core/';
const SERVICE_DIR = ROOT_DIR  . 'service/';
const CACHE_DIR = ROOT_DIR . 'cache/';
const DEBUG_MODE = true;

require_once ROOT_DIR . 'vendor/autoload.php';

\Core\Components\Config::setConfig(require ROOT_DIR . 'config.php');

// Magic!
new \Core\Bootstrap;
