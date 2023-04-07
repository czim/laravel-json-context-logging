<?php

declare(strict_types=1);

namespace Czim\LaravelJsonContextLogging\Loggers;

use Illuminate\Support\Facades\File;
use Psr\Log\LoggerInterface;
use Stringable;

abstract class AbstractLogger implements LoggerInterface
{
    protected const LOG_FILENAME  = 'laravel.log';
    protected const LOGS_SUB_PATH = false;

    public function __construct(protected LoggerInterface $logger)
    {
    }


    /**
     * {@inheritDoc}
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }


    protected function prepareLogPath(): string
    {
        $directory = $this->getDirectory();
        $path      = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getFileName();

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
        if (! is_dir($directory)) {
            File::makeDirectory($directory, $this->directoryPermissions(), true, true);
        }
    }

    protected function directoryPermissions(): int
    {
        return config('json-context-logging.directories.chmod', 0755);
    }
}
