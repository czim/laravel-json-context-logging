<?php
namespace Czim\LaravelJsonContextLogging\Loggers;

use File;
use Psr\Log\LoggerInterface;

abstract class AbstractLogger implements LoggerInterface
{
    const LOG_FILENAME  = 'laravel.log';
    const LOGS_SUB_PATH = false;

    /**
     * Maximum files for rotating file handlers.
     *
     * @var int
     */
    const MAX_FILES = 0;


    /**
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * Create a logger instance
     *
     * @param string          $path
     * @param LoggerInterface $logger
     */
    public function __construct($path, LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     */
    public function emergency($message, array $context = array())
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     */
    public function alert($message, array $context = array())
    {
        $this->logger->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     */
    public function critical($message, array $context = array())
    {
        $this->logger->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     */
    public function error($message, array $context = array())
    {
        $this->logger->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     */
    public function warning($message, array $context = array())
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     */
    public function notice($message, array $context = array())
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     */
    public function info($message, array $context = array())
    {
        $this->logger->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     */
    public function debug($message, array $context = array())
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->logger->log($level, $message, $context);
    }

    /**
     * @return string
     */
    protected function getDirectory()
    {
        return storage_path('logs' . (static::LOGS_SUB_PATH ? '/' . static::LOGS_SUB_PATH : null));
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return static::LOG_FILENAME;
    }

    /**
     * @return string
     */
    protected function prepareLogPath()
    {
        $directory = $this->getDirectory();
        $path      = rtrim($directory, '/') . '/' . $this->getFileName();

        $this->makeSureDirectoryExists($directory);

        return $path;
    }

    /**
     * Checks if directory exists, otherwise creates it.
     *
     * @param  string $directory
     * @return void
     */
    protected function makeSureDirectoryExists($directory)
    {
        if ( ! is_dir($directory)) {
            File::makeDirectory($directory, $mode = 0777, true, true);
        }
    }
}
