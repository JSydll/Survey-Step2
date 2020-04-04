<?php
/**
 * @file
 *
 * @author Joschka Seydell
 * @date 21.03.2020
 */
namespace Step2;

require_once "Step2.php";

/**
 *
 */
final class Logger
{
    private static $instance = null;
    private static $impl = null;
    // Logger parameters with default values
    private static $basePath = __DIR__;
    private static $name = "Global Logger";
    private static $level = \Monolog\Logger::INFO;

    /**
     *
     */
    public static function Configure(string $basePath, string $name, $level = \Monolog\Logger::INFO)
    {
        self::$basePath = $basePath;
        self::$name = $name;
        self::$level = $level;
    }

    /**
     * @brief Gets the singleton instance via lazy initialization for logging
     */
    public static function Log(): Logger
    {
        if (self::$instance === null) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    /**
     *
     */
    public function __construct()
    {
        self::$impl = new \Monolog\Logger(self::$name);
        self::$impl->pushHandler(new \Monolog\Handler\StreamHandler(self::$basePath . "/" . self::$name . ".log", self::$level));
    }

    // Prevent illegal actions
    private function __clone()
    {}
    private function __wakeup()
    {}

    // Wrapper
    public function Critical(string $msg)
    {self::$impl->critical($msg);}
    public function Error(string $msg)
    {self::$impl->error($msg);}
    public function Warning(string $msg)
    {self::$impl->warning($msg);}
    public function Info(string $msg)
    {self::$impl->info($msg);}
    public function Debug(string $msg)
    {self::$impl->debug($msg);}
}
