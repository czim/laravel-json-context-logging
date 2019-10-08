<?php

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

    /**
     * Makes a logger for the given channel.
     *
     * @param string $channel
     * @return LoggerInterface
     */
    public function make(string $channel): LoggerInterface
    {
        $path     = $this->getConfig($channel, 'path');
        $file     = $this->getConfig($channel, 'file');
        $fullPath = rtrim($path, '/') . '/' . $file;


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


        return new Logger($channel, [ $handler ]);
    }

    protected function shouldMakeDirectory(): bool
    {
        return (bool) config('json-context-logging.directories.make_if_not_exists');
    }

    protected function makeDirectory(string $path): void
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if (File::isDirectory($directory)) {
            return;
        }

        $chmod = config('json-context-logging.directories.chmod', 755);

        File::makeDirectory($directory, $chmod, true);
    }

    protected function makeHandler(string $class, ?string $path = null, array $parameters = []): HandlerInterface
    {
        $level = Arr::get($parameters, 'level', Logger::DEBUG);

        switch ($class) {

            case StreamHandler::class:
                try {
                    return new StreamHandler($path, $level);
                } catch (Throwable $e) {
                    throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
                }


            case RotatingFileHandler::class:
                return new RotatingFileHandler(
                    $path,
                    Arr::get($parameters, 'max_files', 0),
                    $level
                );

            default:
                throw new RuntimeException("No support for '{$class}' handler yet.");
        }
    }

    protected function makeFormatter(
        ?string $type,
        ?string $dateFormat,
        ?string $application,
        ?string $defaultCategory
    ): FormatterInterface {

        switch ($type) {

            case 'pure':
                return new PureJsonContextFormatter($dateFormat, $application, $defaultCategory);

            default:
                return new JsonContextFormatter($dateFormat, $application, $defaultCategory);
        }
    }

    /**
     * Returns a config value for a channel with fallback to default.
     *
     * @param string     $channel
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    protected function getConfig(string $channel, string $key, $default = null)
    {
        $channelConfig = config('json-context-logging.channels.' . $channel);

        if ( ! is_array($channelConfig) || ! Arr::has($channelConfig, $key)) {
            return $this->getDefaultConfig($key, $default);
        }

        return Arr::get($channelConfig, $key, $default);
    }

    /**
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    protected function getDefaultConfig(string $key, $default = null)
    {
        return config('json-context-logging.default.' . $key, $default);
    }

}
