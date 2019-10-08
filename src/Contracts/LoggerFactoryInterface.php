<?php

namespace Czim\LaravelJsonContextLogging\Contracts;

use Psr\Log\LoggerInterface;

interface LoggerFactoryInterface
{

    public function make(string $channel): LoggerInterface;

}
