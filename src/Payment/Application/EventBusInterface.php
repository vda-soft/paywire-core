<?php

namespace PayWire\Core\Payment\Application;

use PayWire\Core\Shared\Infrastructure\Event\PublishedEvent;

interface EventBusInterface
{
    /** @param \Generator<mixed, PublishedEvent> $releaseEvents */
    public function commitAll(\Generator $releaseEvents): void;
}
