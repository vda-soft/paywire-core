<?php

namespace PayWire\Core\Payment\Application\Command;

use PayWire\Core\Payment\Application\EventBusInterface;
use PayWire\Core\Payment\Payment;
use PayWire\Core\Payment\PaymentId;
use PayWire\Core\Payment\PaymentRepositoryInterface;
use PayWire\Core\Tests\Shared\Money;

class InitializePaymentHandler
{
    public function __construct(
        private PaymentRepositoryInterface $repository,
        private EventBusInterface $eventBus,
    ) {
    }

    public function __invoke(InitializePayment $command): void
    {
        $payment = Payment::initialize(
            PaymentId::generate(),
            Money::create($command->amount, $command->currency),
            $command->gateway
        );

        $this->repository->save($payment);

        $this->eventBus->commitAll($payment->releaseEvents());
    }
}
