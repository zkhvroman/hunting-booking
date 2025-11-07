<?php

declare(strict_types=1);

namespace Src\ItOps\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;
use function Ramsey\Uuid\v7;

final readonly class Uuid implements \JsonSerializable
{
    /**
     * @param non-empty-string $uuid
     */
    private function __construct(
        private string $uuid,
    ) {}

    /**
     * @phpstan-assert-if-true non-empty-string $uuid
     */
    public static function isUuid(string $uuid): bool
    {
        return RamseyUuid::isValid($uuid);
    }

    public static function fromString(string $uuid): self
    {
        if (self::isUuid($uuid)) {
            return new self($uuid);
        }

        throw new \InvalidArgumentException(\sprintf('String "%s" is not a valid uuid', $uuid));
    }

    public static function nil(): self
    {
        return new self('00000000-0000-0000-0000-000000000000');
    }

    public static function max(): self
    {
        return new self('ffffffff-ffff-ffff-ffff-ffffffffffff');
    }

    public static function v7(?\DateTimeImmutable $time = null): self
    {
        return new self(v7($time));
    }

    /**
     * @return ($value is self ? bool : false)
     */
    public function equals(mixed $value): bool
    {
        return $value instanceof self && $this->uuid === $value->uuid;
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->uuid;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return non-empty-string
     */
    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->uuid;
    }

    public function __serialize(): array
    {
        return [$this->uuid];
    }

    /**
     * @param array{non-empty-string} $data
     */
    public function __unserialize(array $data): void
    {
        [$this->uuid] = $data;
    }
}
