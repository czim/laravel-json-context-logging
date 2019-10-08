<?php
namespace Czim\LaravelJsonContextLogging\Loggers;

use Illuminate\Support\Facades\File;
use Psr\Log\LoggerInterface;

abstract class AbstractLogger implements LoggerInterface
{
    const LOG_FILENAME  = 'laravel.log';
    const LOGS_SUB_PATH = false;


    /**
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     */
    public function emergency($message, array $context = array()): void
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
    public function alert($message, array $context = array()): void
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
    public function critical($message, array $context = array()): void
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
    public function error($message, array $context = array()): void
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
    public function warning($message, array $context = array()): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     */
    public function notice($message, array $context = array()): void
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
    public function info($message, array $context = array()): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     */
    public function debug($message, array $context = array()): void
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
    public function log($level, $message, array $context = array()): void
    {
        $this->logger->log($level, $message, $context);
    }


    protected function prepareLogPath(): string
    {
        $directory = $this->getDirectory();
        $path      = rtrim($directory, '/') . '/' . $this->getFileName();

        $this->makeSureDirectoryExists($directory);

        return $path;
    }

    protected function getDirectory(): string
    {
        return storage_path('logs' . (static::LOGS_SUB_PATH ? '/' . static::LOGS_SUB_PATH : null));
    }

    protected function getFileName(): string
    {
        return static::LOG_FILENAME;
    }

    protected function makeSureDirectoryExists(string $directory): void
    {
        if ( ! is_dir($directory)) {
            File::makeDirectory($directory, $mode = 0777, true, true);
        }
    }
}
