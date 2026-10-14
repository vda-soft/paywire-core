<?php

declare(strict_types=1);

namespace PayWire\Core\Application\Command;

use PayWire\Core\Application\Event\AfterSubmit;
use PayWire\Core\Application\Event\BeforeSubmit;
use PayWire\Core\Application\EventBus;
use PayWire\Core\Application\EventDispatcher;
use PayWire\Core\Application\GatewayResolver;
use PayWire\Core\Domain\Payment\Payment;
use PayWire\Core\Domain\Payment\PaymentRepository;
use PayWire\Core\Domain\Shared\Gateway;
use PayWire\Core\Domain\Shared\SubmissionResult;
use PayWire\Core\Domain\Shared\SubmitRequest;

final class SubmitPaymentHandler
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private GatewayResolver $gatewayResolver,
        private EventBus $eventBus,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function __invoke(SubmitPayment $command): void
    {
        $payment = $this->paymentRepository->findById($command->paymentId);
        if (!$payment->canSubmit()) {
            throw new \LogicException(\sprintf('Payment cannot be submitted. Current status: %s', $payment->status->value));
        }

        $gateway = $this->gatewayResolver->resolve($payment->gateway);

        $submitRequest = $gateway->prepareSubmitRequest($payment);

        $this->eventDispatcher->dispatch(new BeforeSubmit($payment, $submitRequest));

        $submissionResult = $this->submit(
            $gateway,
            $payment,
            $submitRequest
        );

        $this->eventDispatcher->dispatch(new AfterSubmit($payment, $submissionResult));
    }

    private function submit(Gateway $gateway, Payment $payment, SubmitRequest $submitRequest): SubmissionResult
    {
        $response = $gateway->submit($payment, $submitRequest);

        $payment->markSubmitted($response);

        $this->paymentRepository->save($payment);

        $this->eventBus->commitAll($payment->releaseEvents());

        return $response;
    }
}
