<?php

declare(strict_types=1);

namespace Czim\LaravelJsonContextLogging\Factories;

use Czim\LaravelJsonContextLogging\Contracts\LoggerFactoryInterface;
use Czim\MonologJsonContext\Formatters\JsonContextFormatter;
use Czim\MonologJsonContext\Formatters\PureJsonContextFormatter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

class LoggerFactory implements LoggerFactoryInterface
{
    public function make(string $channel): LoggerInterface
    {
        $path     = $this->getConfig($channel, 'path');
        $file     = $this->getConfig($channel, 'file');
        $fullPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;


        if ($this->shouldMakeDirectory()) {
            $this->makeDirectory($path);
        }


        $handlerClass      = $this->getConfig($channel, 'handler.class');
        $handlerParameters = $this->getConfig($channel, 'handler.parameters');

        $handler = $this->makeHandler($handlerClass, $fullPath, $handlerParameters);


        $formatter       = $this->getConfig($channel, 'formatter.type');
        $dateFormat      = $this->getConfig($channel, 'formatter.date_format');
        $application     = $this->getConfig($channel, 'context.application');
        $defaultCategory = $this->getConfig($channel, 'context.category');

        $handler->setFormatter(
            $this->makeFormatter($formatter, $dateFormat, $application, $defaultCategory)
        );


        return new Logger($channel, [$handler]);
    }


    protected function shouldMakeDirectory(): bool
    {
        return (bool) config('json-context-logging.directories.make_if_not_exists');
    }

    protected function directoryRights(): int
    {
        return config('json-context-logging.directories.chmod', 0755);
    }

    protected function logFileRights(): int
    {
        return config('json-context-logging.directories.file_chmod', 0644);
    }

    protected function makeDirectory(string $path): void
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if (File::isDirectory($directory)) {
            return;
        }

        File::makeDirectory($directory, $this->directoryRights(), true);
    }

    /**
     * @param string               $class
     * @param string|null          $path
     * @param array<string, mixed> $parameters
     * @return HandlerInterface
     */
    protected function makeHandler(string $class, ?string $path = null, array $parameters = []): HandlerInterface
    {
        $level = Arr::get($parameters, 'level', Logger::DEBUG);

        switch ($class) {
            case StreamHandler::class:
                try {
                    return new StreamHandler($path, $level, true, $this->logFileRights());
                } catch (Throwable $exception) {
                    throw new RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
                }


            case RotatingFileHandler::class:
                return new RotatingFileHandler(
                    $path,
                    Arr::get($parameters, 'max_files', 0),
                    $level,
                    true,
                    $this->logFileRights()
                );

            default:
                throw new RuntimeException("No support for '{$class}' handler yet.");
        }
    }

    protected function makeFormatter(
        ?string $type,
        ?string $dateFormat,
        ?string $application,
        ?string $defaultCategory,
    ): FormatterInterface {
        return match ($type) {
            'pure'  => new PureJsonContextFormatter($dateFormat, $application, $defaultCategory),
            default => new JsonContextFormatter($dateFormat, $application, $defaultCategory),
        };
    }

    protected function getConfig(string $channel, string $key, mixed $default = null): mixed
    {
        $channelConfig = config('json-context-logging.channels.' . $channel);

        if (! is_array($channelConfig) || ! Arr::has($channelConfig, $key)) {
            return $this->getDefaultConfig($key, $default);
        }

        return Arr::get($channelConfig, $key, $default);
    }

    protected function getDefaultConfig(string $key, mixed $default = null): mixed
    {
        return config('json-context-logging.default.' . $key, $default);
    }
}
