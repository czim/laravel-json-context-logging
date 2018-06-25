<?php

namespace Czim\LaravelJsonContextLogging\Contracts;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{

    /**
     * Makes a logger for the given channel.
     *
     * @param string $channel
     * @return LoggerInterface
     */
    public function make($channel);

}
