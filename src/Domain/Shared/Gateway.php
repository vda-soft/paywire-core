<?php

declare(strict_types=1);

namespace PayWire\Core\Domain\Shared;

use PayWire\Core\Domain\Payment\Payment;

interface Gateway
{
    public function prepareSubmitRequest(Payment $payment): SubmitRequest;

    public function submit(Payment $payment, SubmitRequest $submitRequest): SubmissionResult;
}
