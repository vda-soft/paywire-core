<?php

namespace PayWire\Core\Shared\Infrastructure\Event;

use Doctrine\ORM\EntityManagerInterface;
use PayWire\Core\Payment\Application\EventBusInterface;
use PayWire\Core\Shared\Infrastructure\OutboxMessage;

class OutboxEventBus implements EventBusInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function commitAll(\Generator $releaseEvents): void
    {
        foreach ($releaseEvents as $releaseEvent) {
            $this->dispatch($releaseEvent);
        }

        $this->entityManager->flush();
    }

    private function dispatch(PublishedEvent $event): void
    {
        $outboxMessage = OutboxMessage::fromPublishedEvent($event);

        $this->entityManager->persist($outboxMessage);
    }
}
