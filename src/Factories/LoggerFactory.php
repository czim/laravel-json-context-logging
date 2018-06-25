<?php

namespace Czim\LaravelJsonContextLogging\Factories;

use Czim\LaravelJsonContextLogging\Contracts\LoggerFactoryInterface;
use Czim\MonologJsonContext\Formatters\JsonContextFormatter;
use Czim\MonologJsonContext\Formatters\PureJsonContextFormatter;
use File;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggerFactory implements LoggerFactoryInterface
{

    /**
     * Makes a logger for the given channel.
     *
     * @param string $channel
     * @return LoggerInterface
     */
    public function make($channel)
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

    /**
     * @return bool
     */
    protected function shouldMakeDirectory()
    {
        return (bool) config('json-context-logging.directories.make_if_not_exists');
    }

    /**
     * Recursively creates directories for a given path.
     *
     * @param string $path
     */
    protected function makeDirectory($path)
    {
        $directory = pathinfo($path, PATHINFO_DIRNAME);

        if (File::isDirectory($directory)) {
            return;
        }

        $chmod = config('json-context-logging.directories.chmod', 755);

        File::makeDirectory($directory, $chmod, true);
    }

    /**
     * @param string $class
     * @param string $path
     * @param array  $parameters
     * @return HandlerInterface
     */
    protected function makeHandler($class, $path = null, array $parameters = [])
    {
        $level = array_get($parameters, 'level', Logger::DEBUG);

        switch ($class) {

            case StreamHandler::class:
                try {
                    return new StreamHandler($path, $level);
                } catch (\Exception $e) {
                    throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
                }


            case RotatingFileHandler::class:
                return new RotatingFileHandler(
                    $path,
                    array_get($parameters, 'max_files', 0),
                    $level
                );

            default:
                throw new \RuntimeException("No support for '{$class}'' handler yet.");
        }
    }

    /**
     * @param string      $type
     * @param string|null $dateFormat
     * @param string|null $application
     * @param string|null $defaultCategory
     * @return FormatterInterface
     */
    protected function makeFormatter($type, $dateFormat, $application, $defaultCategory)
    {
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
    protected function getConfig($channel, $key, $default = null)
    {
        $channelConfig = config('json-context-logging.channels.' . $channel);

        if ( ! is_array($channelConfig) || ! array_has($channelConfig, $key)) {
            return $this->getDefaultConfig($key, $default);
        }

        return array_get($channelConfig, $key, $default);
    }

    /**
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    protected function getDefaultConfig($key, $default = null)
    {
        return config('json-context-logging.default.' . $key, $default);
    }

}
