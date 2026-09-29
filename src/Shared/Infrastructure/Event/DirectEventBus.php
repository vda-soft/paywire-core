<?php

namespace PayWire\Core\Shared\Infrastructure\Event;

use Doctrine\ORM\EntityManagerInterface;
use PayWire\Core\Payment\Application\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DirectEventBus implements EventBusInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $realEventBus,
    ) {
    }

    public function commitAll(\Generator $releaseEvents): void
    {
        $this->entityManager->flush();

        foreach ($releaseEvents as $event) {
            $this->realEventBus->dispatch($event);
        }
    }
}
