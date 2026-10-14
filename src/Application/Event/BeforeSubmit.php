<?php

declare(strict_types=1);

namespace PayWire\Core\Application\Event;

use PayWire\Core\Domain\Payment\Payment;
use PayWire\Core\Domain\Shared\SubmitRequest;

readonly class BeforeSubmit
{
    public function __construct(
        public Payment $payment,
        public SubmitRequest $submitRequest,
    ) {
    }
}
