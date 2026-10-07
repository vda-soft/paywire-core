<?php

declare(strict_types=1);

namespace PayWire\Core\Domain\Payment;

use Symfony\Component\Uid\Uuid;

final readonly class PaymentId implements \Stringable, \JsonSerializable
{
    private function __construct(public private(set) string $id)
    {
    }

    public static function generate(): self
    {
        return new self(Uuid::v7()->toString());
    }

    public function __toString(): string
    {
        return $this->id;
    }

    /**
     * @return array{id: string}
     */
    public function jsonSerialize(): array
    {
        return ['id' => $this->id];
    }
}
