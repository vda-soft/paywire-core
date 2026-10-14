<?php

declare(strict_types=1);

namespace PayWire\Core\Application\Event;

use PayWire\Core\Domain\Payment\Payment;
use PayWire\Core\Domain\Shared\SubmissionResult;

readonly class AfterSubmit
{
    public function __construct(
        public Payment $payment,
        public SubmissionResult $submissionResult,
    ) {
    }
}
