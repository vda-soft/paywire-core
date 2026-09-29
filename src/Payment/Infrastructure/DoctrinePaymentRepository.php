<?php

namespace PayWire\Core\Payment\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use PayWire\Core\Payment\Payment;
use PayWire\Core\Payment\PaymentRepositoryInterface;

class DoctrinePaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(Payment $payment): void
    {
        $this->entityManager->persist($payment);
    }
}
