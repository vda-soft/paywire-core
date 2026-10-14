<?php

declare(strict_types=1);

namespace PayWire\Core\Application;

interface EventDispatcher
{
    public function dispatch(object $event): void;
}
