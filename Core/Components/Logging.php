<?php

namespace Core\Components;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Logging
{
    protected static array $logger;

    public static function init($channel) {
        self::$logger[$channel] = new Logger($channel);
        foreach (Config::getLoggerStreams() as $stream) {
            self::$logger[$channel]->pushHandler(self::initHandler($stream));
        }
    }

    public static function initHandler($stream) {
        return new StreamHandler($stream, (Config::debug()) ? Logger::DEBUG : Logger::INFO);
    }

    public function __construct(string $channel) {

    }

    public static function __callStatic($name, $arguments) {
        if (!isset(self::$logger[$arguments[0]])) {
            self::init($arguments[0]);
        }
        call_user_func_array([self::$logger[$arguments[0]], $name], array_slice($arguments, 1));
    }
}